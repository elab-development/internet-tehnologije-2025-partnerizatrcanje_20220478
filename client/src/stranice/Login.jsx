import React, {useEffect, useState} from 'react';
import GlavniNaslov from "../komponente/GlavniNaslov";
import {Button, Form} from "react-bootstrap";
import useForm from "../hooks/useForm";
import server from "../komunikacija/server";
import {toast} from "react-toastify";

const Login = () => {

    const [isLoginPage, setIsLoginPage] = useState(true);
    const title = isLoginPage ? "Login stranica" : "Stranica za registraciju";

    const toggleIsLoginPage = () => {
        setIsLoginPage(!isLoginPage);
    };

    const {formData, handleChange} = useForm({
        name: '',
        email: '',
        password: ''
    });

    const login = () => {
        server.post('/login', {
            email: formData.email,
            password: formData.password
        }).then(response => {
            const data = response.data;
            if (data.uspesno) {
                sessionStorage.setItem('token', data.podaci.token);
                sessionStorage.setItem('user', JSON.stringify(data.podaci.user));
                toast.success(data.poruka);
                window.location.href = '/';
            } else {
                toast.error(data.poruka);
            }
        }).catch(error =>{
            toast.error("Došlo je do greške prilikom prijave.");
        })
    }

    const register = () => {
        server.post('/register', {
            name: formData.name,
            email: formData.email,
            password: formData.password
        }).then(response => {
            const data = response.data;
            if (data.uspesno) {
                toast.success(data.poruka);
                setIsLoginPage(true);
            } else {
                toast.error(data.poruka);
            }
        }).catch(error =>{
            toast.error("Došlo je do greške prilikom registracije.");
        })
    }

    return (
        <div>
            <GlavniNaslov naslov={title} />

            {
                isLoginPage && (
                    <>
                        <Form>
                            <Form.Group className="mb-3" controlId="formBasicEmail">
                                <Form.Label column="lg">Email adresa</Form.Label>
                                <Form.Control name="email" type="email" onChange={handleChange} placeholder="Unesite email" value={formData.email} />
                            </Form.Group>

                            <Form.Group className="mb-3" controlId="formBasicPassword">
                                <Form.Label column="lg">Password</Form.Label>
                                <Form.Control name="password" type="password" onChange={handleChange} placeholder="Password" value={formData.password} />
                            </Form.Group>
                            <Form.Group className="mb-3" controlId="formBasicCheckbox">
                                <Form.Text className="text-muted">
                                    Nemate nalog? <Button variant="link" onClick={toggleIsLoginPage}>Registrujte se ovde</Button>
                                </Form.Text>
                            </Form.Group>
                            <Button onClick={login} variant="primary" type="button">
                                Uloguj se
                            </Button>
                        </Form>
                    </>
                )
            }

            {
                !isLoginPage && (
                    <>
                        <Form>
                            <Form.Group className="mb-3" controlId="formBasicName">
                                <Form.Label column="lg">Ime i prezime</Form.Label>
                                <Form.Control name="name" type="text" onChange={handleChange} placeholder="Unesite ime i prezime" value={formData.name} />
                            </Form.Group>

                            <Form.Group className="mb-3" controlId="formBasicEmail1">
                                <Form.Label column="lg">Email adresa</Form.Label>
                                <Form.Control name="email" type="email" onChange={handleChange} placeholder="Unesite email" value={formData.email} />
                            </Form.Group>

                            <Form.Group className="mb-3" controlId="formBasicPassword1">
                                <Form.Label column="lg">Password</Form.Label>
                                <Form.Control name="password" type="password" onChange={handleChange} placeholder="Password" value={formData.password} />
                            </Form.Group>
                            <Form.Group className="mb-3" controlId="formBasicCheckbox">
                                <Form.Text className="text-muted">
                                    Vec imate nalog? <Button variant="link" onClick={toggleIsLoginPage}>Ulogujte se ovde</Button>
                                </Form.Text>
                            </Form.Group>
                            <Button onClick={register} variant="primary" type="button">
                                Registruj se
                            </Button>
                        </Form>
                    </>
                )
            }

        </div>
    );
};

export default Login;
