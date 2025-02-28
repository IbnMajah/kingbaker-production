<template>
  <div>
    <Head title="Dashboard" />
    <div class="flex justify-between items-center">
      <div v-for="(stat, index) in localStats" :key="index" class="w-full md:w-1/3 m-2">
        <Card :icon="stat.icon" :title="stat.title" :numberValue="stat.numValue" :change="stat.change" />
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Card from '@/Shared/Card.vue'
// import { UsersIcon, CreditCardIcon, BuildingStorefrontIcon } from "@heroicons/vue/24/outline";
// give me the name of the icons based on the title of the stats object
import { TagIcon, ShoppingCartIcon, CurrencyDollarIcon, ChartBarIcon } from "@heroicons/vue/24/outline";
export default {
  components: {
    Head,
    Card,
    TagIcon,
    ShoppingCartIcon,
    CurrencyDollarIcon,
    ChartBarIcon
  },
  layout: Layout,
  props: {
    stats: Object,
  },
  data() {
    return {
      localStats: [
        {
          title: "Total Products",
          numValue: `${this.stats.totalProducts}`,
          icon: ShoppingCartIcon,
          change: `${this.stats.changeProductsFromLastMonth} from last month`,
        },
        {
          title: "Today's Sales",
          numValue: `GMD ${this.stats.todaySales}`,
          icon: CurrencyDollarIcon,
          change: `${this.stats.changeTodaySalesFromLastMonth} from last month`,
        },
        {
          title: "Monthly Revenue",
          numValue: `GMD ${this.stats.monthlyRevenue}`,
          icon: ChartBarIcon,
          change: `${this.stats.changeMonthlyRevenueFromLastMonth} from last month`,
        },
        {
          title: "Active Categories",
          numValue: `${this.stats.activeCategories}`,
          icon: TagIcon,
          change: `${this.stats.changeActiveCategoriesFromLastMonth} from last month`,
        }
      ],
    };
  },
}
</script>
