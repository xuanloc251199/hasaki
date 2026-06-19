/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/**/*.php',
    './public/**/*.html',
  ],
  theme: {
    extend: {
      colors: {
        // ============================================
        // Brand palette derived from user palette:
        //   #880D1E  - dark wine red
        //   #DD2D4A  - primary red (PRIMARY)
        //   #F26A8D  - rose pink
        //   #F49CBB  - light pink
        //   #CBEEF3  - mint cyan accent
        // ============================================
        brand: {
          50:  '#FDF2F4',
          100: '#FCE5EA',
          200: '#F9CDD7',
          300: '#F49CBB',
          400: '#F26A8D',
          500: '#DD2D4A',
          600: '#B81C39',
          700: '#9C1830',
          800: '#880D1E',
          900: '#5C0813',
        },
        mint: {
          50:  '#F0FAFC',
          100: '#E5F5F8',
          200: '#CBEEF3',
          300: '#A8DEE8',
          400: '#7DC9D8',
          500: '#4FAEC2',
          600: '#2D8FA5',
          700: '#1F7385',
          800: '#155566',
          900: '#0E3A47',
        },
        ink: {
          50:  '#f7f7f9',
          100: '#eceef2',
          200: '#dcdfe6',
          300: '#9ea4af',
          400: '#6b7280',
          500: '#4b5563',
          600: '#374151',
          700: '#1f2937',
          800: '#111827',
          900: '#0b1020',
        },
      },
      boxShadow: {
        'card':   '0 2px 6px rgba(136,13,30,.06)',
        'soft':   '0 4px 14px rgba(136,13,30,.08)',
        'lifted': '0 12px 28px rgba(136,13,30,.12)',
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        display: ['Playfair Display', 'Georgia', 'serif'],
      },
      borderRadius: {
        xl: '14px',
      },
      animation: {
        'marquee':  'marquee 35s linear infinite',
        'marquee-slow': 'marquee 60s linear infinite',
      },
      keyframes: {
        marquee: {
          '0%':   { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-50%)' },
        },
      },
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
  },
};
