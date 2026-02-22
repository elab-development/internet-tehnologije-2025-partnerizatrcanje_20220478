import React, {useEffect} from 'react';
import PropTypes from 'prop-types';
import {Button, Card} from "react-bootstrap";
import server from "../komunikacija/server";

const Post = props => {

    const {post, setOdabraniPost} = props;

    return (
        <>
            <Card>
                <Card.Body>
                    <Card.Title>
                        Broj komentara: {post.komentari ? post.komentari.length : 0}
                    </Card.Title>
                    <Card.Text>
                        {post.sadrzaj}
                    </Card.Text>
                    <Card.Text>
                        {post.datum_objave}

                    </Card.Text>

                    <Button variant="primary" onClick={
                        () => setOdabraniPost(post)
                    }>Detalji</Button>
                </Card.Body>
            </Card>
        </>
    );
};

Post.propTypes = {
    post : PropTypes.object.isRequired,
    setOdabraniPost : PropTypes.func.isRequired
};

export default Post;
