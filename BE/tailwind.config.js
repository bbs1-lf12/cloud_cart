/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  darkMode: 'class',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    experimental: {
      optimizeUniversalDefaults: true
    },
    fontFamily: {
      'Roboto': ['Roboto', 'sans-serif']
    },
    extend: {
      colors: {
        primary: {
          light: '#1877F2',
          light_hover: 'rgba(24,119,242,0.7)',
          dark: '#476a9a',
          dark_hover: 'rgba(71,106,154,0.5)'
        },
        secondary: {
          light: '#17A2B8',
          light_hover: 'rgba(23,162,184,0.7)',
          dark: '#acbfd1',
          dark_hover: 'rgba(172,191,209,0.5)'
        },
        accent: {
          light: '#84C318',
          light_hover: 'rgba(132,195,24,0.7)',
          dark: '#e5eef3',
          dark_hover: 'rgba(74,102,26,0.7)'
        },
        success: {
          light: '#28A745',
          light_hover: 'rgba(40,167,69,0.7)',
          dark: '#4a661a',
          dark_hover: 'rgba(74,102,26,0.7)',
        },
        info: {
          light: '#40a6ce',
          light_hover: 'rgba(64,166,206,0.7)',
          dark: '#1e5a71',
          dark_hover: 'rgba(30,90,113,0.9)',
        },
        warning: {
          light: '#FFC107',
          light_hover: 'rgba(255,193,7,0.7)',
          dark: '#b38b1d',
          dark_hover: 'rgba(179,139,29,0.9)',
        },
        error: {
          light: '#DC3545',
          light_hover: 'rgba(220,53,69,0.7)',
          dark: '#8b1e1e',
          dark_hover: 'rgba(139,30,30,0.9)',
        },
        background: {
          light: '#EEEEEE',
          dark: '#31363F'
        },
        componentBackground: {
          light: '#FFFFFF',
          dark: '#404753'
        },
        ctext: {
          light: '#334155',
          dark: '#F9FAFB'
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
