import axios from 'axios';

const geocodingApi = axios.create({
  baseURL: 'https://geocoding-api.open-meteo.com/v1',
  timeout: 20000,
});

const weatherApi = axios.create({
  baseURL: 'https://archive-api.open-meteo.com/v1',
  timeout: 20000,
});

const weatherCodeMap = {
  0: 'Vedro',
  1: 'Pretežno vedro',
  2: 'Delimično oblačno',
  3: 'Oblačno',
  45: 'Magla',
  48: 'Magla sa injem',
  51: 'Slaba rosulja',
  53: 'Umerena rosulja',
  55: 'Jaka rosulja',
  56: 'Slaba ledena rosulja',
  57: 'Jaka ledena rosulja',
  61: 'Slaba kiša',
  63: 'Umerena kiša',
  65: 'Jaka kiša',
  66: 'Slaba ledena kiša',
  67: 'Jaka ledena kiša',
  71: 'Slab sneg',
  73: 'Umeren sneg',
  75: 'Jak sneg',
  77: 'Snežna zrna',
  80: 'Slabi pljuskovi',
  81: 'Umereni pljuskovi',
  82: 'Jaki pljuskovi',
  85: 'Slabi snežni pljuskovi',
  86: 'Jaki snežni pljuskovi',
  95: 'Nevreme',
  96: 'Nevreme sa slabim gradom',
  99: 'Nevreme sa jakim gradom',
};

function formatirajDatum(datum) {
  if (!datum) return null;

  // Ako je već YYYY-MM-DD
  if (/^\d{4}-\d{2}-\d{2}$/.test(datum)) {
    return datum;
  }

  const parsed = new Date(datum);
  if (Number.isNaN(parsed.getTime())) {
    return null;
  }

  return parsed.toISOString().split('T')[0];
}

export async function dohvatiIstorijskoVreme(lokacijaNaziv, datum) {
  const formatiranDatum = formatirajDatum(datum);

  if (!lokacijaNaziv || !formatiranDatum) {
    throw new Error('Lokacija i datum su obavezni.');
  }

  // 1) Pronađi koordinate za lokaciju
  const geoResponse = await geocodingApi.get('/search', {
    params: {
      name: lokacijaNaziv,
      count: 1,
      language: 'sr',
      format: 'json',
    },
  });

  const geoResult = geoResponse?.data?.results?.[0];

  if (!geoResult) {
    throw new Error(`Lokacija "${lokacijaNaziv}" nije pronađena.`);
  }

  const { latitude, longitude, name, country, admin1 } = geoResult;

  // 2) Dohvati istorijsko vreme za taj datum
  const weatherResponse = await weatherApi.get('/archive', {
    params: {
      latitude,
      longitude,
      start_date: formatiranDatum,
      end_date: formatiranDatum,
      timezone: 'auto',
      daily: [
        'weather_code',
        'temperature_2m_max',
        'temperature_2m_mean',
        'temperature_2m_min',
        'precipitation_sum',
        'wind_speed_10m_max',
      ].join(','),
    },
  });

  const daily = weatherResponse?.data?.daily;

  if (!daily || !daily.time || daily.time.length === 0) {
    throw new Error('Podaci o vremenu nisu dostupni za dati datum.');
  }

  return {
    lokacija: {
      naziv: name,
      drzava: country,
      region: admin1 || null,
      latitude,
      longitude,
    },
    datum: daily.time[0],
    vreme: {
      code: daily.weather_code?.[0] ?? null,
      opis: weatherCodeMap[daily.weather_code?.[0]] || 'Nepoznato',
      temperaturaMax: daily.temperature_2m_max?.[0] ?? null,
      temperaturaProsecna: daily.temperature_2m_mean?.[0] ?? null,
      temperaturaMin: daily.temperature_2m_min?.[0] ?? null,
      padavine: daily.precipitation_sum?.[0] ?? null,
      maxBrzinaVetra: daily.wind_speed_10m_max?.[0] ?? null,
    },
  };
}
