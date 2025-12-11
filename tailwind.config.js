const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Mengubah Font Aplikasi
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            
            // === MENGGANTI WARNA UTAMA KE TEAL ===
            colors: {
                // Definisikan Primary sebagai Teal (cocok untuk Kesehatan)
                primary: colors.teal, 
                
                // Gunakan default Tailwind colors jika ada kode yang masih memanggil 'indigo'
                // Ini mencegah crash, tapi kita akan ganti Indigo ke Teal
                indigo: colors.teal, 
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};