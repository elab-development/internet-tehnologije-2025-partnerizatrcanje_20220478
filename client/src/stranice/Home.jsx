import React from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import {Col, Image, Row} from "react-bootstrap";
import slikaTrka from "../slike/homePhoto.jpg";

const Home = () => {
    return (
        <div>
            <GlavniNaslov naslov="Dobro došli na stranicu za upravljanje trkama!" />
            <Row>
                <Col md={6}>
                    <Image src={slikaTrka} alt="Trka" fluid />
                </Col>
                <Col md={6} className="d-flex align-items-center">
                    <div>
                        <h2>O RunApp-u</h2>
                        <p>
                            RunApp je vaša ultimativna destinacija za upravljanje trkama i učešćem u njima.
                            Bilo da ste strastveni trkač ili organizator događaja, naš sistem vam pruža sve
                            potrebne alate za jednostavno upravljanje trkama, registraciju učesnika i praćenje rezultata.
                        </p>
                        <p>
                            Pridružite se našoj zajednici danas i iskusite kako je lako organizovati i učestvovati
                            u trkama sa RunApp-om!
                        </p>
                        <ul>
                            <li>Pregled i upravljanje trkama</li>
                            <li>Registracija učesnika</li>
                            <li>Praćenje rezultata</li>
                            <li>Korisnički postovi i istorija učešća</li>
                        </ul>
                    </div>
                </Col>
            </Row>
        </div>
    );
};

export default Home;
