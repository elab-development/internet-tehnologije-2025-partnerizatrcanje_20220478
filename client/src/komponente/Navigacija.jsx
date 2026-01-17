import React from 'react';
import {Container, Image, Nav, Navbar} from "react-bootstrap";
import logo from '../slike/runnapp-logo.png';

const Navigacija = () => {

    const token = sessionStorage.getItem('token');
    const user = token ? JSON.parse(sessionStorage.getItem('user')) : null;
    const admin = user ? user.tipKorisnika === 'admin' : false;

    const logout = (e) => {
        e.preventDefault();
        sessionStorage.removeItem('token');
        sessionStorage.removeItem('user');
        window.location.href = '/';
    }

    return (
        <>
            <Navbar expand="lg" className="bg-body-tertiary">
                <Container>
                    <Navbar.Brand href="/"><Image src={logo} alt="RunApp" width={150} height={120}/> </Navbar.Brand>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="me-auto">
                            <Nav.Link href="/">Home</Nav.Link>
                            <Nav.Link href="/o-nama">O nama</Nav.Link>
                            <Nav.Link href="/lokacije">Lokacije</Nav.Link>
                            <Nav.Link href="/trke">Trke</Nav.Link>

                            {
                                token && (
                                    <>
                                        <Nav.Link href="/moja-ucesca">Moja ucesca</Nav.Link>
                                    </>
                                )
                            }

                            {
                                admin && (
                                    <>
                                        <Nav.Link href="/admin">Administracija</Nav.Link>
                                    </>
                                )
                            }

                            {
                                !token ? (
                                    <>
                                        <Nav.Link href="/login">Login</Nav.Link>
                                    </>
                                ) : (
                                    <>
                                        <Nav.Link href="#" onClick={logout}>Logout</Nav.Link>
                                    </>
                                )
                            }

                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
        </>
    );
};

export default Navigacija;
