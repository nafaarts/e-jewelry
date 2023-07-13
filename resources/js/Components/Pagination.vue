<script>
export default {
    props: {
        links: Array,
    },
};
</script>

<template>
    <nav v-show="links.length > 3">
        <ul class="inline-flex items-center -space-x-px">
            <li>
                <Component
                    :is="link.url ? 'Link' : 'span'"
                    v-for="(link, index) in links"
                    :key="index"
                    :href="link.url"
                    class="px-3 py-2 leading-tight border"
                    :class="{
                        'bg-gray-100 cursor-not-allowed': !link.url,
                        'hover:bg-gray-100 hover:text-gray-700': link.url,
                        'text-orange-600 border-orange-300 bg-orange-50 hover:bg-orange-100 hover:text-orange-700':
                            link.active,
                        'text-gray-500': !link.active,
                        'rounded-l-lg': index == 0,
                        'rounded-r-lg': index == links.length - 1,
                    }"
                    v-html="
                        index == 0
                            ? `<i class='fas fa-chevron-left'></i>`
                            : index == links.length - 1
                            ? `<i class='fas fa-chevron-right'></i>`
                            : link.label
                    "
                />
            </li>
        </ul>
    </nav>
</template>
