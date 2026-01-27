import React, {useEffect} from 'react';
import {
    MapContainer,
    TileLayer,
    Marker,
    Popup
} from 'react-leaflet';
import GlavniNaslov from "../komponente/GlavniNaslov";
import server from "../komunikacija/server";

const Lokacije = () => {
    const position = [44.787197, 20.457273];

    const [lokacije, setLokacije] = React.useState([]);

    useEffect(() => {
        server.get('/lokacije').then(response => {
            const data = response.data;
            if (data.uspesno) {
                setLokacije(data.podaci);
            }
        }).catch(error => {
            console.error("Došlo je do greške prilikom učitavanja lokacija.");
        });
    }, []);



    return (
        <>
            <GlavniNaslov naslov="Lokacije za trcanje" />
            <MapContainer center={position} zoom={13} scrollWheelZoom={false}>
                <TileLayer
                    attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                {
                    lokacije.length > 0 && lokacije.map((lokacija) => (
                        <>
                            <Marker key={lokacija.id} position={[lokacija.lat, lokacija.long]}>
                                <Popup>
                                    {lokacija.naziv}
                                </Popup>
                            </Marker>
                        </>
                    ))
                }
            </MapContainer>
        </>
    );
};

export default Lokacije;