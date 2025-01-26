<div
    id="scroll-up"
    x-data="{ show: false, offset: 1000 }"
    x-on:scroll.window="show = window.pageYOffset >= offset"
    class="fixed start-8 bottom-8 z-50 sm:start-14 lg:start-32 xl:start-40 2xl:start-48"
>
    <button
        x-show="show"
        x-transition.duration.500ms
        x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="cursor-pointer rounded-full bg-blue-700 p-2"
    >
        <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            stroke="white"
            stroke-width="1.5"
            stroke-miterlimit="10"
            stroke-linecap="round"
            stroke-linejoin="round"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path d="M18.0702 11.07L12.0002 5L5.93018 11.07" />
            <path d="M12 19L12 6" />
        </svg>
    </button>
</div>
