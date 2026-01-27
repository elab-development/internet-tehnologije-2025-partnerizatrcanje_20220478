import React, {useEffect} from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import server from "../komunikacija/server";
import {Table} from "react-bootstrap";
import {toast} from "react-toastify";

const MojaUcesca = () => {

    const [mojaUcesca, setMojaUcesca] = React.useState([]);

    useEffect(() => {
        const user = JSON.parse(sessionStorage.getItem('user'));
        server.get(`/users/${user.id}/ucesca`).then(response => {
            const data = response.data;
            if (data.uspesno) {
                setMojaUcesca(data.podaci);
            }
        }).catch(error => {
            console.error("Došlo je do greške prilikom učitavanja mojih učešća.");
        });
    }, []);

    const otkaziUcesce = (ucesce) => {
        server.delete(`/ucesca/${ucesce.id}`)
            .then(response => {
                const data = response.data;
                console.log(data);
                if (data.uspesno) {
                    setMojaUcesca(mojaUcesca.filter(u => u.id !== ucesce.id));
                    toast.success("Uspešno otkazano učešće.");
                } else {
                    toast.error("Došlo je do greške prilikom otkazivanja učešća.");
                }
            })
            .catch(error => {
                toast.error("Došlo je do greške prilikom otkazivanja učešća.");
            })
    }

    return (
        <div>
            <GlavniNaslov  naslov="Moja učešća" />

                    <Table hover>
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
                            mojaUcesca.length > 0 ? mojaUcesca.map((ucesce) => (
                                <tr key={ucesce.id}>
                                    <td>{ucesce.trka.naziv}</td>
                                    <td>{ucesce.user.name}</td>
                                    <td>{ucesce.vreme} sati</td>
                                    <td>
                                        {
                                            new Date(ucesce.trka.datum) > new Date() && (
                                                <>
                                                    <button className="btn btn-danger btn-sm"
                                                            onClick={() => {
                                                                otkaziUcesce(ucesce)
                                                            }}>
                                                        Otkazi učešće
                                                    </button>
                                                </>
                                            )
                                        }
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan="3" className="text-center">Nema učešća za trke.</td>
                                </tr>
                            )
                        }
                        </tbody>
                    </Table>
        </div>
    );
};

export default MojaUcesca;
