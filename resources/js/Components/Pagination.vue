<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
});
</script>
<template>
    <div class="m-2" v-if="links.length > 3">
        
        <nav
            class="pagination d-flex justify-items-center justify-content-between"
            role="navigation"
            aria-label="pagination"
        >
            <ul class="pagination">
                <template v-for="(link, key) in links">
                    <li v-if="link.url === '...'" :key="key">
                        <span class="pagination-ellipsis">&hellip;</span>
                    </li>
                    <li v-else :class="{'active': link.active}">
                        <Link
                            preserve-scroll
                            preserve-state
                            :key="`link-${key}`"
                            class="page-link"
                            :aria-label="'Goto page ' + link.label"
                            :class="{ 'is-current': link.active }"
                            :aria-current="link.active ? 'page' : '#'"
                            :href="link.url || '#'"
                            v-html="link.label"
                        />
                    </li>
                </template>
            </ul>
        </nav>
    </div>
</template>