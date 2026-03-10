import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom';
import Footer from '../Footer';

describe('Footer komponenta', () => {
  test('prikazuje trenutnu godinu i tekst o pravima', () => {
    render(<Footer />);

    const currentYear = new Date().getFullYear();

    expect(screen.getByText(new RegExp(`${currentYear}`))).toBeInTheDocument();
    expect(screen.getByText(/Sva prava zadržana\./i)).toBeInTheDocument();
  });

  test('renderuje footer element', () => {
    const { container } = render(<Footer />);

    const footerElement = container.querySelector('footer');
    expect(footerElement).toBeInTheDocument();
  });
});
