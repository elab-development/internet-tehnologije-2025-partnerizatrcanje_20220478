import React, {useEffect} from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import server from "../komunikacija/server";
import {toast} from "react-toastify";
import {Col, Row, Table} from "react-bootstrap";

const Administracija = () => {

    const [link, setLink] = React.useState("/ucesca/paginacija");
    const [ucesca, setUcesca] = React.useState([]);
    const [dugmici, setDugmici] = React.useState([]);

    const obrisiUcesce = (ucesce) => {
        server.delete(`/ucesca/${ucesce.id}`)
            .then(response => {
                const data = response.data;
                console.log(data);
                if (data.uspesno) {
                    setUcesca(ucesca.filter(u => u.id !== ucesce.id));
                    toast.success("Uspešno otkazano učešće.");
                } else {
                    toast.error("Došlo je do greške prilikom otkazivanja učešća.");
                }
            })
            .catch(error => {
                toast.error("Došlo je do greške prilikom otkazivanja učešća.");
            })
    }

    useEffect(() => {
        server.get(link).then(response => {
            const data = response.data;
            if (data.uspesno) {
                setUcesca(data.podaci.data);
                const links = data.podaci.links;

                const dugmici = links.map(link => ({
                    label: link.label === "&laquo; Previous" ? "Prethodna" : link.label === "Next &raquo;" ? "Sledeća" : link.label,
                    url: link.url,
                    active: link.active
                }));

                setDugmici(dugmici);
            }else{
                toast.error("Došlo je do greške prilikom učitavanja učešća.");
            }
        }).catch(error => {
            toast.error("Došlo je do greške prilikom učitavanja učešća.");
        });
    }, [link]);

    return (
        <div>
            <GlavniNaslov naslov="Administracija" />
            <Row>
                <Table>
                    <thead>
                    <tr>
                        <th>Trka</th>
                        <th>Trkac</th>
                        <th>Vreme</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody>
                    {
                        ucesca.length > 0 && ucesca.map((ucesce) => (
                            <tr key={ucesce.id}>
                                <td>{ucesce.trka_naziv}</td>
                                <td>{ucesce.user_name}</td>
                                <td>{ucesce.vreme} sati</td>
                                <td>
                                    <button className="btn btn-danger" onClick={() => {
                                        obrisiUcesce(ucesce)
                                    }}>Otkazi</button>
                                </td>
                            </tr>
                        ))
                    }
                    </tbody>
                </Table>
            </Row>
            <Row>
                <Col>
                {
                    dugmici.length > 0 && dugmici.map((dugme) => (
                        <>
                                <button key={dugme.label} className={`m-1 btn btn-${dugme.active ? 'primary' : 'secondary'}`} onClick={() => {
                                    setLink(dugme.url);
                                }} disabled={
                                    !dugme.url
                                }>{dugme.label}</button>
                        </>

                    ))
                }
                </Col>
            </Row>
        </div>
    );
};

export default Administracija;
