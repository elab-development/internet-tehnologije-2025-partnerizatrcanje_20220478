import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import GlavniNaslov from '../GlavniNaslov';

describe('GlavniNaslov komponenta', () => {
  test('prikazuje prosleđeni naslov', () => {
    render(<GlavniNaslov naslov='Test naslov' />);

    expect(screen.getByRole('heading', { level: 1 })).toHaveTextContent(
      'Test naslov',
    );
  });

  test('ima div sa klasom glavni-naslov', () => {
    const { container } = render(<GlavniNaslov naslov='Naslov' />);

    const wrapper = container.querySelector('.glavni-naslov');
    expect(wrapper).toBeInTheDocument();
  });
});
