<template>
    <v-container>
        <h2 class="mb-4">Login</h2>

        <v-form @submit.prevent="login">
            <v-text-field v-model="email" label="Email" required></v-text-field>
            <v-text-field
                v-model="password"
                label="Password"
                type="password"
                required
            ></v-text-field>
            <v-btn type="submit" color="primary">Login</v-btn>
        </v-form>
    </v-container>
</template>

<script>
import axios from '@/axios';
import authService from '@/../services/auth';

export default {
    data() {
        return {
            email: '',
            password: '',
        };
    },
    methods: {
        async login() {
            try {
                await axios.get('/sanctum/csrf-cookie');

                const response = await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                });

                const token = response.data.token;

                localStorage.setItem('token', token);

                authService.setAuthorizationToken(token);

                this.$router.push({ name: 'Home' });
            } catch (error) {
                console.error('Login error:', error.response ? error.response.data : error.message);
                alert('Invalid credentials');
            }
        },
    },
};
</script>
