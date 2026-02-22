import React, {useEffect} from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import server from "../komunikacija/server";
import {toast} from "react-toastify";
import {Accordion, Button, Col, Form, Row} from "react-bootstrap";
import Post from "../komponente/Post";
import useForm from "../hooks/useForm";

const Postovi = () => {

    const [postovi, setPostovi] = React.useState([]);
    const [odabraniPost, setOdabraniPost] = React.useState(null);
    const [odabranoUcesce, setOdabranoUcesce] = React.useState(null);
    const [komentari, setKomentari] = React.useState([]);

    const {formData, handleChange} = useForm({
        komentar: ''
    });

    const dodajKomentar = (e) => {
        e.preventDefault();
        if(odabraniPost == null){
            toast.error("Nije odabran post za komentarisanje.");
            return;
        }
        //datum komentara yyyy-mm-dd HH:mm:ss
        server.post('/komentari', {
            komentar: formData.komentar,
            post_id: odabraniPost.id,
            user_id: JSON.parse(sessionStorage.getItem('user')).id,
            ocena : 0,
            datum_komentara : new Date().toISOString().slice(0, 19).replace('T', ' ')
        }).then(response => {
            const data = response.data;
            if (data.uspesno) {
                toast.success(data.poruka);
                setKomentari([...komentari, data.podaci]);
            } else {
                toast.error(data.poruka);
            }
        }).catch(error => {
            toast.error("Došlo je do greške prilikom dodavanja komentara.");
        }
        );
    }

    useEffect(() => {
         if(odabraniPost != null){
             server.get('/ucesca/' + odabraniPost.ucesce.id).then(
                 response => {
                     const data = response.data;
                     if (data.uspesno) {
                         setOdabranoUcesce(data.podaci);
                     }
                 }).catch(error => {
                     console.log("Došlo je do greške prilikom učitavanja učešća.");
                 }
             )
         }
    }, [odabraniPost]);

    useEffect(() => {
        server.get('/postovi').then(response => {
            const data = response.data;
            if (data.uspesno) {
                setPostovi(data.podaci);
            }
        }).catch(error => {
            toast.error("Došlo je do greške prilikom učitavanja postova.");
        });
    }, []);

    useEffect(() => {
        if(odabraniPost != null){
            server.get('/komentari/' + odabraniPost.id).then(response => {
                const data = response.data;
                if (data.uspesno) {
                    setKomentari(data.podaci);
                }

            }).catch(error => {
                toast.error("Došlo je do greške prilikom učitavanja komentara.");
            });
        }
    }, [odabraniPost]);

    return (
        <>
            <GlavniNaslov naslov="Postovi" />
            {
                !odabraniPost && (
                    <>
                        <Row>
                            {
                                postovi.length > 0 && postovi.map((post) => (
                                    <>
                                        <Col key={post.id} md={4} className="mb-4">
                                            <Post post={post} setOdabraniPost={setOdabraniPost} />
                                        </Col>
                                    </>
                                ))
                            }
                        </Row>
                    </>
                )
            }

            {
                odabraniPost && (
                    <>
                        <h1>Trka : {odabranoUcesce ? odabranoUcesce.trka.naziv : ""}</h1>
                        <h2>Trkač : {odabranoUcesce ? odabranoUcesce.user.name : ""}</h2>
                        <p>{odabraniPost.sadrzaj}</p>
                        <p>{odabraniPost.datum_objave}</p>

                        <Accordion defaultActiveKey="0">
                            {
                                komentari.length > 0 && komentari.map((komentar, index) => (
                                    <>
                                        <Accordion.Item eventKey={komentar.id}>
                                            <Accordion.Header>{
                                                komentar.user.name + " - " + komentar.datum_komentara
                                            }</Accordion.Header>
                                            <Accordion.Body>
                                                {komentar.komentar}
                                                {
                                                    komentar.user.id === JSON.parse(sessionStorage.getItem('user')).id && (
                                                        <div className="mt-2">
                                                            <Button variant="danger" size="sm" onClick={() => {
                                                                server.delete('/komentari/' + komentar.id).then(response => {
                                                                    const data = response.data;
                                                                    if (data.uspesno) {
                                                                        toast.success(data.poruka);
                                                                        setKomentari(komentari.filter(k => k.id !== komentar.id));
                                                                    } else {
                                                                        toast.error(data.poruka);
                                                                    }
                                                                }).catch(error => {
                                                                    toast.error("Došlo je do greške prilikom brisanja komentara.");
                                                                });
                                                            }}>Obriši komentar</Button>
                                                        </div>
                                                    )
                                                }
                                            </Accordion.Body>
                                        </Accordion.Item>
                                    </>
                                ))
                            }

                        </Accordion>
                        <hr/>

                        <Form className="mb-3">
                                <Form.Group className="mb-3" controlId="komentar">
                                    <Form.Label column="lg">Dodaj komentar</Form.Label>
                                    <Form.Control as="textarea" name="komentar" onChange={
                                        handleChange
                                    } rows={3} placeholder="Unesite komentar..." />
                                </Form.Group>
                                <button className="btn btn-primary" onClick={
                                    dodajKomentar
                                }>Dodaj komentar</button>
                        </Form>

                    </>
                )
            }

            <Button onClick={() => setOdabraniPost(null)} variant="secondary" className="mt-3 mb-3">Nazad na postove</Button>
        </>
    );
};

export default Postovi;
