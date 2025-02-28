<template>
  <div>
    <Head :title="form.name" />
    <div class="flex justify-start mb-8 max-w-3xl">
      <h1 class="text-3xl font-bold">
        <Link class="text-brand-400 hover:text-brand-600" href="/products">Products</Link>
        <span class="text-brand-400 font-medium">/</span>
        {{ form.name }}
      </h1>
    </div>
    <trashed-message v-if="product.deleted_at" class="mb-6" @restore="restore"> 
      This product has been deleted. 
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <div class="pb-8 pr-6 w-full lg:w flex items-end">
            <text-input
              v-model="form.quantity"
              :error="form.errors.quantity"
              class="flex-1 mr-4"
              label="Quantity"
              type="number"
              readonly
            />
            <button
              type="button"
              class="btn-kingbaker h-12"
              @click="showAddQuantityModal = true"
            >
              Add Quantity
            </button>
          </div>
          <text-input 
            v-model="form.name" 
            :error="form.errors.name" 
            class="pb-8 pr-6 w-full lg:w"
            label="Name" 
          />
          <text-input 
            v-model="form.code" 
            :error="form.errors.code" 
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Code" 
          />
          <text-input 
            v-model="form.description" 
            :error="form.errors.description" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Description" 
          />
          <text-input 
            v-model="form.cost_price" 
            :error="form.errors.cost_price" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Cost Price" 
            type="number"
          />
          <text-input 
            v-model="form.selling_price" 
            :error="form.errors.selling_price" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Selling Price" 
            type="number"
          />

          <select-input 
            v-model="form.category_id" 
            :error="form.errors.category_id" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Category"
          >
            <option :value="null" />
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select-input>
          <select-input 
            v-model="form.branch_id" 
            :error="form.errors.branch_id" 
            class="pb-8 pr-6 w-full lg:w-1/2" 
            label="Branch"
          >
            <option :value="null" />
            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
              {{ branch.name }}
            </option>
          </select-input>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button 
            v-if="!product.deleted_at" 
            class="text-red-600 hover:underline" 
            tabindex="-1" 
            type="button" 
            @click="destroy"
          >
            Delete Product
          </button>
          <loading-button 
            :loading="form.processing" 
            class="btn-kingbaker ml-auto" 
            type="submit"
          >
            Update Product
          </loading-button>
        </div>
      </form>
    </div>
    <add-quantity-modal
      :show="showAddQuantityModal"
      :product-id="product.id"
      @close="showAddQuantityModal = false"
    />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import AddQuantityModal from '@/Shared/AddQuantityModal.vue'


export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    AddQuantityModal
  },
  layout: Layout,
  props: {
    product: Object,
    categories: Array,
    branches: Array,
  },
  remember: 'form',
  data() {
    return {
      showAddQuantityModal: false,
      form: this.$inertia.form({
        _method: 'put',
        name: this.product.name,
        code: this.product.code,
        description: this.product.description,
        cost_price: this.product.cost_price,
        selling_price: this.product.selling_price,
        quantity: this.product.quantity,
        category_id: this.product.category_id,
        branch_id: this.product.branch_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.post(`/products/${this.product.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this product?')) {
        this.$inertia.delete(`/products/${this.product.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this product?')) {
        this.$inertia.put(`/products/${this.product.id}/restore`)
      }
    },
  },
}
</script>
