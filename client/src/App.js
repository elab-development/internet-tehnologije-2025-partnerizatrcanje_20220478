import logo from './logo.svg';
import 'bootstrap/dist/css/bootstrap.min.css';
import './App.css';
import Navigacija from "./komponente/Navigacija";
import {BrowserRouter, Route, Routes} from "react-router-dom";
import Home from "./stranice/Home";
import Login from "./stranice/Login";
import ONama from "./stranice/ONama";
import MojaUcesca from "./stranice/MojaUcesca";
import Lokacije from "./stranice/Lokacije";
import Administracija from "./stranice/Administracija";
import Trke from "./stranice/Trke";
import {Container} from "react-bootstrap";
import Footer from "./komponente/Footer";
import {Bounce, ToastContainer} from "react-toastify";

function App() {

  return (
    <>
      <Navigacija />
        <Container className="glavni-sadrzaj mt-4">
            <BrowserRouter>
                <Routes>
                    <Route element={<Home />} path="/" />
                    <Route element={<Login />} path="/login" />
                    <Route element={<ONama />} path="/o-nama" />
                    <Route element={<MojaUcesca />} path="/moja-ucesca" />
                    <Route element={<Lokacije />} path="/lokacije" />
                    <Route element={<Administracija />} path="/administracija" />
                    <Route element={<Trke/>} path="/trke" />
                </Routes>
            </BrowserRouter>
        </Container>
        <ToastContainer
            position="bottom-right"
            autoClose={5000}
            hideProgressBar={false}
            newestOnTop={false}
            closeOnClick={false}
            rtl={false}
            pauseOnFocusLoss
            draggable
            pauseOnHover
            theme="dark"
            transition={Bounce}
        />
        <Footer />
    </>
  );
}

export default App;
