import React, {useEffect} from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import {Tab, Table, Tabs} from "react-bootstrap";
import server from "../komunikacija/server";

const Trke = () => {

    const [buduceTrke, setBuduceTrke] = React.useState([]);
    const [prethodneTrke, setPrethodneTrke] = React.useState([]);
    const [mojaUcesca, setMojaUcesca] = React.useState([]);
    const [izabranaTrka, setIzabranaTrka] = React.useState(null);
    const [ucescaZaIzabranuTrku, setUcescaZaIzabranuTrku] = React.useState([]);

    const user = JSON.parse(sessionStorage.getItem('user'));

    useEffect(() => {
        server.get('/trke/buduce').then(response => {
            const data = response.data;
            if (data.uspesno) {
                setBuduceTrke(data.podaci);
            }
        }).catch(error => {
            console.error("Došlo je do greške prilikom učitavanja budućih trka.");
        });

        server.get('/trke').then(response => {
            const data = response.data;
            if (data.uspesno) {
                setPrethodneTrke(data.podaci);

            }
        }).catch(error => {
            console.error("Došlo je do greške prilikom učitavanja prethodnih trka.");
        });
    }, []);

    useEffect(() => {
        const user = JSON.parse(sessionStorage.getItem('user'));
        if (user) {
            server.get(`/users/${user.id}/ucesca`).then(response => {
                const data = response.data;
                if (data.uspesno) {
                    console.log(data.podaci);
                    setMojaUcesca(data.podaci);
                }
            }).catch(error => {
                console.error("Došlo je do greške prilikom učitavanja mojih učešća.");
            });
        }
    }, []);

    const prijaviSeNaTrku = (trkaId) => {
        const user = JSON.parse(sessionStorage.getItem('user'));
        if (user) {
            server.post(`/ucesca`, {
                user_id: user.id,
                trka_id: trkaId,
                vreme: 0
            }).then(response => {
                const data = response.data;
                if (data.uspesno) {
                    const novoUcesce = data.podaci;
                    setMojaUcesca([...mojaUcesca, novoUcesce]);
                }
            }).catch(error => {
                console.error("Došlo je do greške prilikom prijave na trku.");
            });
        }
    }

    useEffect(() => {
        if (izabranaTrka) {
            server.get(`/trke/${izabranaTrka.id}/ucesca`).then(response => {
                const data = response.data;
                if (data.uspesno) {
                    setUcescaZaIzabranuTrku(data.podaci);
                }
            }).catch(
                error => {
                    console.error("Došlo je do greške prilikom učitavanja učešća za izabranu trku.");
                }
            )
        }
    }, [izabranaTrka]);


    return (
        <div>
            <GlavniNaslov naslov="Trke stranica" />
            <Tabs
                defaultActiveKey="buduce"
                id="uncontrolled-tab-example"
                className="mb-3"
            >
                <Tab eventKey="buduce" title="Buduće trke">
                    <Table hover>
                        <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Datum</th>
                            <th>Lokacija</th>
                            <th>Distanca (km)</th>
                            <th>Organizator</th>
                            <th>
                                Akcije
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {
                            buduceTrke.length > 0 ? buduceTrke.map((trka) => (
                                <tr key={trka.id}>
                                    <td>{trka.naziv}</td>
                                    <td>{trka.datum}</td>
                                    <td>{trka.lokacija.naziv}</td>
                                    <td>{trka.kilometraza}</td>
                                    <td>{trka.organizator}</td>
                                    <td>
                                        {
                                           user !== null && mojaUcesca.some(ucesce => ucesce.trka.id === trka.id) ? (
                                                <span className="text-success">Prijavljen/a</span>
                                            ) : (
                                               <>
                                                   {
                                                       user !== null ? (
                                                           <button className="btn btn-primary btn-sm" onClick={
                                                               () => prijaviSeNaTrku(trka.id)
                                                           }>Prijavi se</button>
                                                       ) : (
                                                              <span className="text-muted">Ulogujte se da biste se prijavili na trku</span>
                                                         )
                                                   }
                                               </>
                                            )
                                        }
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="6" className="text-center">Nema budućih trka.</td>
                                </tr>
                            )
                        }
                        </tbody>
                    </Table>
                </Tab>
                <Tab eventKey="prethodne" title="Sve trke">
                    {
                        !izabranaTrka && (
                            <Table hover>
                                <thead>
                                <tr>
                                    <th>Naziv</th>
                                    <th>Datum</th>
                                    <th>Lokacija</th>
                                    <th>Distanca (km)</th>
                                    <th>Organizator</th>
                                    <th>Akcije</th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    prethodneTrke.length > 0 ? prethodneTrke.map((trka) => (
                                        <tr key={trka.id}>
                                            <td>{trka.naziv}</td>
                                            <td>{trka.datum}</td>
                                            <td>{trka.lokacija.naziv}</td>
                                            <td>{trka.kilometraza}</td>
                                            <td>{trka.organizator}</td>
                                            <td>
                                                {
                                                    user !== null && (
                                                        <button className="btn btn-info btn-sm" onClick={
                                                            () => setIzabranaTrka(trka)
                                                        }>Pogledaj učešća</button>
                                                    )
                                                }
                                            </td>
                                        </tr>
                                    )) : (
                                        <tr>
                                            <td colSpan="5" className="text-center">Nema prethodnih trka.</td>
                                        </tr>
                                    )
                                }
                                </tbody>
                            </Table>
                        )
                    }

                    {
                        izabranaTrka && (
                            <>
                                <Table hover>
                                    <thead>
                                        <tr>
                                            <th>Trka</th>
                                            <th>Lokacija</th>
                                            <th>Trkac</th>
                                            <th>Vreme</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            ucescaZaIzabranuTrku.length > 0 ? ucescaZaIzabranuTrku.map((ucesce) => (
                                                <tr key={ucesce.id}>
                                                    <td>{izabranaTrka.naziv}</td>
                                                    <td>{izabranaTrka.lokacija.naziv}</td>
                                                    <td>{ucesce.user.name}</td>
                                                    <td>{ucesce.vreme} sati</td>
                                                </tr>
                                            )) : (
                                                <tr>
                                                    <td colSpan="3" className="text-center">Nema učešća za izabranu trku.</td>
                                                </tr>
                                            )
                                        }
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colSpan="4">
                                                <button className="btn btn-secondary btn-sm" onClick={
                                                    () => setIzabranaTrka(null)
                                                }>Nazad na sve trke</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </Table>
                            </>
                        )
                    }
                </Tab>
            </Tabs>
        </div>
    );
};

export default Trke;
