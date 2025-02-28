<template>
    <div>
      <Modal :show="show" @close="close">
        <div class="p-6">
          <h2 class="text-lg font-medium text-gray-900">
            Add Quantity
          </h2>
          <div class="mt-6">
            <form @submit.prevent="submit">
              <text-input
                v-model="form.quantity"
                :error="form.errors.quantity"
                type="number"
                label="Quantity to Add"
                class="pb-8 pr-6 w-full"
              />
              <div class="mt-6 flex justify-end">
                <button
                  type="button"
                  class="btn-secondary mr-2"
                  @click="close"
                >
                  Cancel
                </button>
                <loading-button
                  :loading="form.processing"
                  class="btn-kingbaker"
                  type="submit"
                >
                  Add Quantity
                </loading-button>
              </div>
            </form>
          </div>
        </div>
      </Modal>
    </div>
  </template>
  
  <script>
  import Modal  from '@/Shared/Modal.vue'
  import TextInput from '@/Shared/TextInput.vue'
  import LoadingButton from '@/Shared/LoadingButton.vue'
  
  export default {
    components: {
      Modal,
      TextInput,
      LoadingButton,
    },
    props: {
      show: Boolean,
      productId: Number,
    },
    emits: ['close'],
    data() {
      return {
        form: this.$inertia.form({
          quantity: '',
        }),
      }
    },
    methods: {
      close() {
        this.form.reset()
        this.$emit('close')
      },
      submit() {
        this.form.put(`/products/${this.productId}/add-quantity`, {
          preserveScroll: true,
          onSuccess: () => this.close(),
        })
      },
    },
  }
  </script>