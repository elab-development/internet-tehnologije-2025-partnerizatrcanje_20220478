import React from 'react';
import { fireEvent, render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';

jest.mock('../../komunikacija/server', () => ({
  get: jest.fn(),
  post: jest.fn(),
  delete: jest.fn(),
}));

import Post from '../Post';

describe('Post komponenta', () => {
  const mockPost = {
    id: 1,
    sadrzaj: 'Ovo je test sadržaj posta',
    datum_objave: '2026-03-10',
    komentari: [
      { id: 1, komentar: 'Komentar 1' },
      { id: 2, komentar: 'Komentar 2' },
    ],
  };

  test('prikazuje broj komentara, sadržaj i datum objave', () => {
    render(<Post post={mockPost} setOdabraniPost={jest.fn()} />);

    expect(screen.getByText(/Broj komentara: 2/i)).toBeInTheDocument();
    expect(screen.getByText(/Ovo je test sadržaj posta/i)).toBeInTheDocument();
    expect(screen.getByText(/2026-03-10/i)).toBeInTheDocument();
  });

  test('prikazuje 0 komentara kada komentari nisu prosleđeni', () => {
    const postBezKomentara = {
      id: 2,
      sadrzaj: 'Post bez komentara',
      datum_objave: '2026-03-11',
    };

    render(<Post post={postBezKomentara} setOdabraniPost={jest.fn()} />);

    expect(screen.getByText(/Broj komentara: 0/i)).toBeInTheDocument();
  });

  test('poziva setOdabraniPost kada se klikne na dugme Detalji', () => {
    const mockSetOdabraniPost = jest.fn();

    render(<Post post={mockPost} setOdabraniPost={mockSetOdabraniPost} />);

    fireEvent.click(screen.getByRole('button', { name: /Detalji/i }));

    expect(mockSetOdabraniPost).toHaveBeenCalledTimes(1);
    expect(mockSetOdabraniPost).toHaveBeenCalledWith(mockPost);
  });
});
