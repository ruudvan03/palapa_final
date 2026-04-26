import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import BookingForm from './components/BookingForm';

const bookingContainer = document.getElementById('react-booking-form');

if (bookingContainer) {
    const root = createRoot(bookingContainer);
    root.render(<BookingForm />);
}