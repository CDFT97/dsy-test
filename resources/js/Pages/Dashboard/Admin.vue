<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header> Dashboard </template>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-6 border-b border-gray-200">
        <h2 class="p-3 text-lg font.medium text-hray-900">
          Select month and year
        </h2>
        <div class="p-3 w-2/4 mt-1 pb-0">
          <InputLabel for="year" value="Year:">Year</InputLabel>
          <TextInput
            @change="validateMonth"
            type="number"
            id="number"
            ref="yearInput"
            min="1990"
            max="2023"
            step="1"
            v-model="form.year"
            class="mt-1 block w-3/4"
            placeholder="2023"
          ></TextInput>
          <InputError :message="form.errors.year" class="mt-2"></InputError>
        </div>

        <div class="p-3 w-2/4 pb-5">
          <InputLabel for="type" value="Tipo:"></InputLabel>
          <SelectInput
            id="type"
            v-model="form.month"
            @update="updateMonth"
            class="mt-1 block w-full"
            :options="month_list"
            placeholder="Select a month"
          ></SelectInput>
          <InputError :message="form.errors.type" class="mt-2"></InputError>
        </div>

        <PrimaryButton :disabled="form.processing" @click="submit">
          Search
        </PrimaryButton>

        <SecondaryButton class="ms-3" @click="exportToCsv">
          Export To Csv
        </SecondaryButton>

        <br />
        <br />

        <table class="table-auto border border-gray-400" style="width: 95%">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-2 py-2">Date</th>
              <th class="px-2 py-2">Value</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in dolar_price_by_month"
              :key="item.Valor"
              class="border-t-2 border-gray-200"
            >
              <td class="px-2 py-2">{{ item.Fecha }}</td>
              <td class="px-2 py-2">{{ item.Valor }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useAlertsStore } from "@/Stores/alerts";
import axios from "axios";
const alerts = useAlertsStore();
const props = defineProps({
  dolar_price_by_month: [],
});

const form = useForm({
  year: "",
  month: "",
  data: [],
});

const month_list = ref([
  {
    id: "01",
    name: "January",
  },
  {
    id: "02",
    name: "February",
  },
  {
    id: "03",
    name: "March",
  },
  {
    id: "04",
    name: "April",
  },
  {
    id: "05",
    name: "May",
  },
  {
    id: "06",
    name: "June",
  },
  {
    id: "07",
    name: "July",
  },
  {
    id: "08",
    name: "August",
  },
  {
    id: "09",
    name: "September",
  },
  {
    id: "10",
    name: "October",
  },
  {
    id: "11",
    name: "November",
  },
  {
    id: "12",
    name: "December",
  },
]);

const validateMonth = () => {
  if (form.year == "2023") {
    month_list.value = [
      {
        id: "01",
        name: "January",
      },
      {
        id: "02",
        name: "February",
      },
      {
        id: "03",
        name: "March",
      },
      {
        id: "04",
        name: "April",
      },
      {
        id: "05",
        name: "May",
      },
      {
        id: "06",
        name: "June",
      },
      {
        id: "07",
        name: "July",
      },
      {
        id: "08",
        name: "August",
      },
      {
        id: "09",
        name: "September",
      },
      {
        id: "10",
        name: "October",
      },
    ];
  }
};
const submit = () => {
  if (form.year == "" || form.month == "") {
    return alert("Please select a year and month");
  }
  form.post(route("sbif.get-price-by-month"), {
    onSuccess: () => alerts.success("Done!"),
  });
};

const updateMonth = (e) => {
  form.month = e;
  console.log(form.year, form.month);
};

const exportToCsv = async () => {
  const body = {
    data: props.dolar_price_by_month,
  };
  console.log(body);
  try {
    const { data } = await axios({
      method: "post",
      data: body,
      url: route("sbif.export-to-csv"),
      config: { headers: { "Content-Type": "multipart/form-data" } },
      responseType: "blob",
    });

    const href = URL.createObjectURL(data);
    const link = document.createElement("a");
    link.href = href;
    link.setAttribute("download", `dolar dates from year: ${form.year} month:  ${form.month}.csv`); //or any other extension
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(href);
    alerts.success("Done!")
  } catch (error) {
    console.log(error);
  }
};
</script>