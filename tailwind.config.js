import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/View/ComponentDefinition.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        'clean-dark': '#355872',
        'clean-soft': '#7AAACE',
        'clean-bright': '#9CD5FF',
        'clean-bg': '#F7F8F0',
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
};
