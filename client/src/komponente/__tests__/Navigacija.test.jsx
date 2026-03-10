import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import Navigacija from '../Navigacija';

jest.mock('../../slike/runnapp-logo.png', () => 'test-logo.png');

describe('Navigacija komponenta', () => {
  beforeEach(() => {
    sessionStorage.clear();

    Object.defineProperty(window, 'location', {
      writable: true,
      value: { href: '' },
    });
  });

  test('prikazuje javne linkove i Login kada korisnik nije ulogovan', () => {
    render(<Navigacija />);

    expect(screen.getByText(/Home/i)).toBeInTheDocument();
    expect(screen.getByText(/O nama/i)).toBeInTheDocument();
    expect(screen.getByText(/Lokacije/i)).toBeInTheDocument();
    expect(screen.getByText(/Trke/i)).toBeInTheDocument();
    expect(screen.getByText(/Login/i)).toBeInTheDocument();

    expect(screen.queryByText(/Postovi/i)).not.toBeInTheDocument();
    expect(screen.queryByText(/Moja ucesca/i)).not.toBeInTheDocument();
    expect(screen.queryByText(/Administracija/i)).not.toBeInTheDocument();
    expect(screen.queryByText(/Logout/i)).not.toBeInTheDocument();
  });

  test('prikazuje korisničke linkove i Logout kada je korisnik ulogovan', () => {
    sessionStorage.setItem('token', 'test-token');
    sessionStorage.setItem(
      'user',
      JSON.stringify({
        id: 1,
        name: 'Nikola',
        tipKorisnika: 'trkac',
      }),
    );

    render(<Navigacija />);

    expect(screen.getByText(/Postovi/i)).toBeInTheDocument();
    expect(screen.getByText(/Moja ucesca/i)).toBeInTheDocument();
    expect(screen.getByText(/Logout/i)).toBeInTheDocument();

    expect(screen.queryByText(/Login/i)).not.toBeInTheDocument();
    expect(screen.queryByText(/Administracija/i)).not.toBeInTheDocument();
  });

  test('prikazuje Administracija link kada je korisnik admin', () => {
    sessionStorage.setItem('token', 'admin-token');
    sessionStorage.setItem(
      'user',
      JSON.stringify({
        id: 1,
        name: 'Admin',
        tipKorisnika: 'admin',
      }),
    );

    render(<Navigacija />);

    expect(screen.getByText(/Administracija/i)).toBeInTheDocument();
    expect(screen.getByText(/Logout/i)).toBeInTheDocument();
  });

  test('logout briše token i user iz sessionStorage i radi redirect', () => {
    sessionStorage.setItem('token', 'test-token');
    sessionStorage.setItem(
      'user',
      JSON.stringify({
        id: 1,
        name: 'Nikola',
        tipKorisnika: 'trkac',
      }),
    );

    render(<Navigacija />);

    fireEvent.click(screen.getByText(/Logout/i));

    expect(sessionStorage.getItem('token')).toBeNull();
    expect(sessionStorage.getItem('user')).toBeNull();
    expect(window.location.href).toBe('/');
  });
});
