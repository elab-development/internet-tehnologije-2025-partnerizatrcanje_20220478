import React from 'react';
import {FaDollarSign, FaDolly, FaHeart} from "react-icons/fa";

const Footer = () => {
    const year = new Date().getFullYear();
    return (
        <>
            <footer>
                <p> <FaHeart /> {year} Sva prava zadržana.</p>
            </footer>
        </>
    );
};

export default Footer;
