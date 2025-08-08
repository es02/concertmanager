<script setup>
import { router } from '@inertiajs/vue3';
import { FwbButton, FwbInput, FwbSelect } from 'flowbite-vue'

const props = defineProps(['tenant', 'plan', 'plans', 'trial']);

var form = {
    'name': props.tenant.name,
    'fqdn': props.tenant.fqdn,
    'plan_id': props.tenant.plan_id,
};

function update() { router.post('/settings/update', form);};
</script>

<template>
    <fwb-input v-model="form.name" label="Tenant name" required /><br/>
    <fwb-input v-model="form.fqdn" label="DNS name" required /><br/>
    <div class="mb-4">
        <fwb-button type="submit" @click="update()">Update Tenant</fwb-button>
    </div>
    <div class="py-12">
        <span class="font-semibold">Current Plan:</span>
        <div class="mt-4 font-normal flex flex-col justify-between p-4 leading-normal">
                <p v-if="plan.name !== null"><span class="font-semibold">Name:</span> {{ plan.name }}</p>
                <p v-if="plan.price !== null && plan.price !== 0"><span class="font-semibold">Price:</span> {{ plan.price }} / {{ plan.period || 0 }} days</p>
                <p v-if="plan.price !== null && plan.price === 0"><span class="font-semibold">Price:</span> Free</p>
                <p v-if="plan.trial !== null && plan.trial !== 0"><span class="font-semibold">Trial period remaining:</span> {{ trial }} / {{ plan.trial_length }} days</p>
            </div>
        <fwb-select v-model="form.plan_id" :options="plans" label="Change Plan" /><br/>
        <div class="mb-4">
            <fwb-button type="submit" @click="update()">Update Tenant</fwb-button>
        </div>
    </div>
</template>
