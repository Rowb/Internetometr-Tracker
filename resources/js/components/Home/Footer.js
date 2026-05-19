import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Container, Row } from 'react-bootstrap';
import { Col } from 'react-bootstrap';
import Version from './Version';

export default class Footer extends Component {
    render() {
        return (
            <Container>
                <Row>
                    <Col sm={{ span: 12 }} className="text-center">
                        <Version />
                        <p className="text-muted">
                            <a href="https://github.com/Rowb/internetometer-tracker" target="_blank" rel="noopener noreferrer">Internetometer Tracker</a>
                            {' · '}
                            <a href="https://github.com/Master290/internetometer-cli" target="_blank" rel="noopener noreferrer">internetometer-cli</a>
                            {' · '}
                            <a href="https://yandex.ru/internet" target="_blank" rel="noopener noreferrer">Яндекс Интернетометр</a>
                        </p>
                    </Col>
                </Row>
            </Container>
        );
    }
}

if (document.getElementById('Footer')) {
    ReactDOM.render(<Footer />, document.getElementById('Footer'));
}
