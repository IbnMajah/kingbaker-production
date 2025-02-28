<template>
  <div>
    <Head title="Daily Records" />
    <h1 class="mb-8 text-3xl font-bold">Daily Records</h1>

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4 w-full max-w-3xl">
          <search-filter v-model="form.search" class="w-full max-w-md" @reset="reset">
            <label class="block text-gray-700">Trashed:</label>
            <select v-model="form.trashed" class="form-select mt-1 w-full">
              <option :value="null">All Records</option>
              <option value="with">With Trashed</option>
              <option value="only">Only Trashed</option>
            </select>
          </search-filter>

          <div class="w-full max-w-xs">
            
            <div class="flex items-center gap-2">
              <input
                v-model="form.record_date"
                type="date"
                class="form-input w-full"
              >
              <button 
                v-if="form.record_date"
                @click="clearDateFilter"
                class="text-gray-500 hover:text-gray-700"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <Link class="btn-kingbaker" href="/daily-records/create">
          <span>Create</span>
          <span class="hidden md:inline">&nbsp;Daily Record</span>
        </Link>
      </div>
    
    <!-- Empty state message when no records exist -->
    <div v-if="dailyRecords.data.length === 0" class="bg-white rounded-md shadow p-6 text-center">
      <p class="text-gray-600 text-lg">
        {{ form.search || form.trashed || form.record_date ? 'No records found matching your filters.' : 'Create your first daily production record to get started.' }}
      </p>
     
    </div>

    <!-- Show regular interface when records exist -->
    <template v-else>
      
      
      <div class="bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Date</th>
            <th class="pb-4 pt-6 px-6">Number of Products</th>
            <th class="pb-4 pt-6 px-6">Total Revenue</th>
            <th class="pb-4 pt-6 px-6"></th>
          </tr>
          <tr v-for="record in dailyRecords.data" :key="record.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
              <Link class="flex items-center px-6 py-4 focus:text-brand-500" :href="`/daily-records/${record.id}/edit`">
                {{ formatDate(record.record_date) }}
                <icon v-if="record.deleted_at" name="trash" class="shrink-0 ml-2 w-3 h-3 fill-gray-400" />
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/daily-records/${record.id}/edit`" tabindex="-1">
                <div>
                  {{ record.number_of_products }}
                </div>
              </Link>
            </td>
            <td class="border-t">
              <Link class="flex items-center px-6 py-4" :href="`/daily-records/${record.id}/edit`" tabindex="-1">
                GMD {{ record.total_revenue }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link class="flex items-center px-4" :href="`/daily-records/${record.id}/edit`" tabindex="-1">
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
        </table>
      </div>
      <pagination class="mt-6" :links="dailyRecords.links" />
    </template>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    dailyRecords: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        record_date: this.filters.record_date || '',
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function() {
        this.$inertia.get('/daily-records', pickBy(this.form), {
          preserveState: true,
          preserveScroll: true,
        })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    clearDateFilter() {
      this.form.record_date = null
    },
    filterByDate() {
      this.$inertia.get('/daily-records', pickBy(this.form), {
        preserveState: true,
        preserveScroll: true,
      })
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      
      const day = date.getDate()
      const dayOfWeek = days[date.getDay()]
      const month = months[date.getMonth()]
      const year = date.getFullYear()
      
      // Get time components
      let hours = date.getHours()
      const minutes = date.getMinutes()
      const ampm = hours >= 12 ? 'PM' : 'AM'
      
      // Convert to 12-hour format
      hours = hours % 12
      hours = hours ? hours : 12 // the hour '0' should be '12'
      
      // Add leading zero to minutes if needed
      const formattedMinutes = minutes < 10 ? '0' + minutes : minutes
      
      // Function to add ordinal suffix (th, st, nd, rd)
      const getOrdinalSuffix = (n) => {
        const s = ['th', 'st', 'nd', 'rd']
        const v = n % 100
        return s[(v - 20) % 10] || s[v] || s[0]
      }
      
        //   return `${dayOfWeek}. ${day}${getOrdinalSuffix(day)}, ${month}. ${year} ${hours}:${formattedMinutes} ${ampm}`
        return `${dayOfWeek}. ${day}${getOrdinalSuffix(day)}, ${month}. ${year}`
    },
  },
}
</script>
