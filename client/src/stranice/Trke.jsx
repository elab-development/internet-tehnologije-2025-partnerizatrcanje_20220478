import React, { useEffect } from 'react';
import GlavniNaslov from '../komponente/GlavniNaslov';
import { Tab, Table, Tabs } from 'react-bootstrap';
import server from '../komunikacija/server';
import { dohvatiIstorijskoVreme } from '../api/vremenskaPrognoza';

const Trke = () => {
  const [buduceTrke, setBuduceTrke] = React.useState([]);
  const [prethodneTrke, setPrethodneTrke] = React.useState([]);
  const [mojaUcesca, setMojaUcesca] = React.useState([]);
  const [izabranaTrka, setIzabranaTrka] = React.useState(null);
  const [ucescaZaIzabranuTrku, setUcescaZaIzabranuTrku] = React.useState([]);

  const [vremePoTrci, setVremePoTrci] = React.useState({});
  const [ucitavaVreme, setUcitavaVreme] = React.useState({});

  const user = JSON.parse(sessionStorage.getItem('user'));

  useEffect(() => {
    server
      .get('/trke/buduce')
      .then((response) => {
        const data = response.data;
        if (data.uspesno) {
          setBuduceTrke(data.podaci);
        }
      })
      .catch(() => {
        console.error('Došlo je do greške prilikom učitavanja budućih trka.');
      });

    server
      .get('/trke')
      .then((response) => {
        const data = response.data;
        if (data.uspesno) {
          setPrethodneTrke(data.podaci);
        }
      })
      .catch(() => {
        console.error(
          'Došlo je do greške prilikom učitavanja prethodnih trka.',
        );
      });
  }, []);

  useEffect(() => {
    const user = JSON.parse(sessionStorage.getItem('user'));
    if (user) {
      server
        .get(`/users/${user.id}/ucesca`)
        .then((response) => {
          const data = response.data;
          if (data.uspesno) {
            setMojaUcesca(data.podaci);
          }
        })
        .catch(() => {
          console.error('Došlo je do greške prilikom učitavanja mojih učešća.');
        });
    }
  }, []);

  const prijaviSeNaTrku = (trkaId) => {
    const user = JSON.parse(sessionStorage.getItem('user'));
    if (user) {
      server
        .post(`/ucesca`, {
          user_id: user.id,
          trka_id: trkaId,
          vreme: 0,
        })
        .then((response) => {
          const data = response.data;
          if (data.uspesno) {
            const novoUcesce = data.podaci;
            setMojaUcesca([...mojaUcesca, novoUcesce]);
          }
        })
        .catch(() => {
          console.error('Došlo je do greške prilikom prijave na trku.');
        });
    }
  };

  useEffect(() => {
    if (izabranaTrka) {
      server
        .get(`/trke/${izabranaTrka.id}/ucesca`)
        .then((response) => {
          const data = response.data;
          if (data.uspesno) {
            setUcescaZaIzabranuTrku(data.podaci);
          }
        })
        .catch(() => {
          console.error(
            'Došlo je do greške prilikom učitavanja učešća za izabranu trku.',
          );
        });
    }
  }, [izabranaTrka]);

  const prikaziVremeZaTrku = async (trka) => {
    if (vremePoTrci[trka.id]) {
      return;
    }

    try {
      setUcitavaVreme((prev) => ({
        ...prev,
        [trka.id]: true,
      }));

      const podaci = await dohvatiIstorijskoVreme(
        trka.lokacija.naziv,
        trka.datum,
      );

      setVremePoTrci((prev) => ({
        ...prev,
        [trka.id]: podaci,
      }));
    } catch (error) {
      console.error('Greška pri učitavanju vremenske prognoze:', error);
    } finally {
      setUcitavaVreme((prev) => ({
        ...prev,
        [trka.id]: false,
      }));
    }
  };

  return (
    <div>
      <GlavniNaslov naslov='Trke stranica' />

      <Tabs
        defaultActiveKey='buduce'
        id='uncontrolled-tab-example'
        className='mb-3'
      >
        <Tab eventKey='buduce' title='Buduće trke'>
          <Table hover>
            <thead>
              <tr>
                <th>Naziv</th>
                <th>Datum</th>
                <th>Lokacija</th>
                <th>Distanca (km)</th>
                <th>Organizator</th>
                <th>Akcije</th>
              </tr>
            </thead>
            <tbody>
              {buduceTrke.length > 0 ? (
                buduceTrke.map((trka) => (
                  <tr key={trka.id}>
                    <td>{trka.naziv}</td>
                    <td>{trka.datum}</td>
                    <td>{trka.lokacija.naziv}</td>
                    <td>{trka.kilometraza}</td>
                    <td>{trka.organizator}</td>
                    <td>
                      {user !== null &&
                      mojaUcesca.some(
                        (ucesce) => ucesce.trka.id === trka.id,
                      ) ? (
                        <span className='text-success'>Prijavljen/a</span>
                      ) : (
                        <>
                          {user !== null ? (
                            <button
                              className='btn btn-primary btn-sm'
                              onClick={() => prijaviSeNaTrku(trka.id)}
                            >
                              Prijavi se
                            </button>
                          ) : (
                            <span className='text-muted'>
                              Ulogujte se da biste se prijavili na trku
                            </span>
                          )}
                        </>
                      )}
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan='6' className='text-center'>
                    Nema budućih trka.
                  </td>
                </tr>
              )}
            </tbody>
          </Table>
        </Tab>

        <Tab eventKey='prethodne' title='Sve trke'>
          {!izabranaTrka && (
            <Table hover>
              <thead>
                <tr>
                  <th>Naziv</th>
                  <th>Datum</th>
                  <th>Lokacija</th>
                  <th>Distanca (km)</th>
                  <th>Organizator</th>
                  <th>Akcije</th>
                </tr>
              </thead>
              <tbody>
                {prethodneTrke.length > 0 ? (
                  prethodneTrke.flatMap((trka) => {
                    const redovi = [
                      <tr key={`trka-${trka.id}`}>
                        <td>{trka.naziv}</td>
                        <td>{trka.datum}</td>
                        <td>{trka.lokacija.naziv}</td>
                        <td>{trka.kilometraza}</td>
                        <td>{trka.organizator}</td>
                        <td>
                          {user !== null && (
                            <button
                              className='btn btn-info btn-sm me-2'
                              onClick={() => setIzabranaTrka(trka)}
                            >
                              Pogledaj učešća
                            </button>
                          )}

                          <button
                            className='btn btn-outline-primary btn-sm'
                            onClick={() => prikaziVremeZaTrku(trka)}
                            disabled={ucitavaVreme[trka.id]}
                          >
                            {ucitavaVreme[trka.id]
                              ? 'Učitavanje...'
                              : 'Prikaži vreme'}
                          </button>
                        </td>
                      </tr>,
                    ];

                    if (vremePoTrci[trka.id]) {
                      redovi.push(
                        <tr key={`vreme-${trka.id}`}>
                          <td colSpan='6'>
                            <div className='p-3 border rounded bg-light'>
                              <h6 className='mb-3'>
                                Vremenski uslovi za datum{' '}
                                {vremePoTrci[trka.id].datum}
                              </h6>
                              <div>
                                <strong>Opis:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.opis}
                              </div>
                              <div>
                                <strong>Maksimalna temperatura:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.temperaturaMax} °C
                              </div>
                              <div>
                                <strong>Prosečna temperatura:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.temperaturaProsecna}{' '}
                                °C
                              </div>
                              <div>
                                <strong>Minimalna temperatura:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.temperaturaMin} °C
                              </div>
                              <div>
                                <strong>Padavine:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.padavine} mm
                              </div>
                              <div>
                                <strong>Maksimalna brzina vetra:</strong>{' '}
                                {vremePoTrci[trka.id].vreme.maxBrzinaVetra} km/h
                              </div>
                            </div>
                          </td>
                        </tr>,
                      );
                    }

                    return redovi;
                  })
                ) : (
                  <tr>
                    <td colSpan='6' className='text-center'>
                      Nema prethodnih trka.
                    </td>
                  </tr>
                )}
              </tbody>
            </Table>
          )}

          {izabranaTrka && (
            <Table hover>
              <thead>
                <tr>
                  <th>Trka</th>
                  <th>Lokacija</th>
                  <th>Trkač</th>
                  <th>Vreme</th>
                </tr>
              </thead>
              <tbody>
                {ucescaZaIzabranuTrku.length > 0 ? (
                  ucescaZaIzabranuTrku.map((ucesce) => (
                    <tr key={ucesce.id}>
                      <td>{izabranaTrka.naziv}</td>
                      <td>{izabranaTrka.lokacija.naziv}</td>
                      <td>{ucesce.user.name}</td>
                      <td>{ucesce.vreme} sati</td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan='4' className='text-center'>
                      Nema učešća za izabranu trku.
                    </td>
                  </tr>
                )}
              </tbody>
              <tfoot>
                <tr>
                  <td colSpan='4'>
                    <button
                      className='btn btn-secondary btn-sm'
                      onClick={() => setIzabranaTrka(null)}
                    >
                      Nazad na sve trke
                    </button>
                  </td>
                </tr>
              </tfoot>
            </Table>
          )}
        </Tab>
      </Tabs>
    </div>
  );
};

export default Trke;
