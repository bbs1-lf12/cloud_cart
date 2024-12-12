/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  darkMode: 'class',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          light: '#133E87',
          light_hover: 'rgba(19,62,135,0.5)',
          dark: '#476a9a',
          dark_hover: 'rgba(71,106,154,0.5)'
        },
        secondary: {
          light: '#608BC1',
          light_hover: 'rgba(96,139,193,0.5)',
          dark: '#acbfd1',
          dark_hover: 'rgba(172,191,209,0.5)'
        },
        accent: {
          light: '#CBDCEB',
          dark: '#e5eef3'
        },
        success: {
          light: '#99cc33',
          light_hover: 'rgba(153,204,51,0.9)',
          dark: '#4a661a',
          dark_hover: 'rgba(74,102,26,0.7)',
        },
        info: {
          light: '#40a6ce',
          light_hover: 'rgba(64,166,206,0.9)',
          dark: '#1e5a71',
          dark_hover: 'rgba(30,90,113,0.9)',
        },
        warning: {
          light: '#f9e154',
          light_hover: 'rgba(249,225,84,0.90)',
          dark: '#b38b1d',
          dark_hover: 'rgba(179,139,29,0.9)',
        },
        error: {
          light: '#f02c2c',
          light_hover: 'rgba(240,44,44,0.9)',
          dark: '#8b1e1e',
          dark_hover: 'rgba(139,30,30,0.9)',
        },
        background: {
          light: '#EEEEEE',
          dark: '#31363F'
        },
        componentBackground: {
          light: '#FFFFFF',
          dark: '#27374D'
        },
        ctext: {
          light: '#111827',
          dark: '#F9FAFB'
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
