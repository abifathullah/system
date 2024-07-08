<template>
    <v-container>
        <h2 class="mb-4 font-semibold text-xl text-black leading-tight">
            <v-btn flat color="primary" @click="goBack">Back</v-btn>
            Product Detail
        </h2>

        <v-card class="elevation-0 border">
            <v-card-title v-if="product">{{ product.name }}</v-card-title>
            <v-card-title v-else>Loading...</v-card-title>

            <v-card-text v-if="product">
                <p>ID: {{ product.id }}</p>
                <p>Category: {{ product.category.name }}</p>
                <p>Name: {{ product.name }}</p>
                <p>Price: {{ product.price }}</p>
                <p>Description: {{ product.description }}</p>

                <v-btn @click="deleteProduct" color="error" class="my-4">Delete Product</v-btn>
                <v-btn @click="goToEditProduct" color="primary" class="ml-4">Edit Product</v-btn>
            </v-card-text>
            <v-card-text v-else>
                <p>Loading product data...</p>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
    name: 'ProductDetail',

    data() {
        return {
            product: null,
            checkInterval: null,
        };
    },

    created() {
        this.fetchProduct();
    },

    methods: {
        fetchProduct() {
            axios
                .get(`/api/products/${this.$route.params.id}`, {
                    params: {
                        with: 'category',
                    },
                })
                .then((response) => {
                    if (response.data.message) {
                        this.checkInterval = setInterval(this.checkProductData, 5000);
                    } else {
                        this.product = response.data;
                    }
                })
                .catch((error) => {
                    console.error('Error fetching product:', error);
                });
        },

        checkProductData() {
            axios
                .get(`/api/products/${this.$route.params.id}`, {
                    params: {
                        with: 'category',
                    },
                })
                .then((response) => {
                    if (!response.data.message) {
                        this.product = response.data;
                        clearInterval(this.checkInterval);
                    }
                })
                .catch((error) => {
                    console.error('Error checking product data:', error);
                });
        },

        deleteProduct() {
            if (confirm('Are you sure you want to delete this product?')) {
                axios
                    .delete(`/api/products/${this.$route.params.id}`)
                    .then(() => {
                        const toast = useToast();
                        toast.success('Product deleted successfully');
                        this.goBack();
                    })
                    .catch((error) => {
                        const toast = useToast();
                        toast.error('Error deleting product');
                        console.error('Error deleting product:', error);
                    });
            }
        },

        goToEditProduct() {
            this.$router.push({ name: 'EditProduct', params: { id: this.product.id } });
        },

        goBack() {
            this.$router.push({ name: 'Home' });
        },
    },

    beforeUnmount() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }
    },
};
</script>

<style scoped>
.elevation-0 {
    box-shadow: none;
}

.border {
    border: 1px solid #e0e0e0;
}
</style>
