import React from 'react';
import PropTypes from 'prop-types';

const GlavniNaslov = props => {
    return (
        <>
            <div className="glavni-naslov">
                <h1>{props.naslov}</h1>
            </div>
        </>
    );
};

GlavniNaslov.propTypes = {
    naslov: PropTypes.string.isRequired
};

export default GlavniNaslov;
