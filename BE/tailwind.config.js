/** @type {import('tailwindcss').Config} */
module.exports = {
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
          dark: '#476a9a',
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
          light: '#cfecdc',
          dark: '#e5f3eb'
        },
        info: {
          light: '#d0e1ec',
          dark: '#e5eff3'
        },
        warning: {
          light: '#faf9eb',
          dark: '#f3f2e5'
        },
        error: {
          light: '#ecd0d0',
          dark: '#f3e6e5'
        },
        background: {
          light: '#F3F3E0',
          dark: '#2a2a25'
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
