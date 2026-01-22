import React from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import {Col, Row} from "react-bootstrap";
import team from "../slike/tim.jpeg";

const ONama = () => {
    return (
        <div>
            <GlavniNaslov naslov="O Nama" />
            <Row>
                <Col md={6}>
                    Mi smo grupa studenata sa Fakulteta organizacionih nauka u Beogradu, koja je razvila aplikaciju za upravljanje trkama pod nazivom RunApp. Naš cilj je da olakšamo organizaciju i učešće u trkama kroz jednostavan i efikasan sistem.
                    <br /><br />
                    Naš tim se sastoji od iskusnih programera i entuzijasta trčanja, koji su posvećeni stvaranju najboljeg mogućeg iskustva za naše korisnike. Verujemo da će RunApp postati nezaobilazan alat za sve trkače i organizatore trka.
                    <br /><br />
                    Hvala vam što koristite RunApp i radujemo se što ćemo vam pomoći da ostvarite vaše trkačke ciljeve!
                </Col>
                <Col md={6}>
                     <img src={team} alt="Tim" className="img img-thumbnail" />
                 </Col>
            </Row>
        </div>
    );
};

export default ONama;
