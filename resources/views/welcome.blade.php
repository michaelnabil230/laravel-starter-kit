<x-app-layout>
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden">
        <div aria-hidden="true" class="absolute inset-s-1/2 -top-96 flex -translate-x-1/2 transform">
            <div
                class="h-176 w-100 -translate-x-40 rotate-[-60deg] transform bg-linear-to-r from-violet-300/50 to-purple-100 blur-3xl"
            ></div>
            <div
                class="rounded-fulls h-200 w-360 origin-top-left -translate-x-60 -rotate-12 bg-linear-to-tl from-blue-50 via-blue-100 to-blue-50 blur-3xl"
            ></div>
        </div>

        <div class="relative z-10">
            <div class="mx-auto max-w-340 px-4 py-10 sm:px-6 lg:px-8 lg:py-16">
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="inline-block bg-linear-to-l from-blue-600 to-violet-500 bg-clip-text text-sm font-medium text-transparent"
                    >PRO release - Dashboard</p>

                    <div class="mt-5 max-w-2xl">
                        <h1 class="block text-4xl font-semibold text-gray-800 md:text-5xl lg:text-6xl">
                            The Advanced Dashboard
                        </h1>
                    </div>

                    <div class="mt-5 max-w-3xl">
                        <p class="text-lg text-gray-600">Dashboard is a fully responsive and customizable admin template built with Tailwind CSS and Vue.js with Inertia.js.</p>
                    </div>

                    <div class="mt-8 flex justify-center gap-3">
                        @auth
                            <a
                                href="{{ route('dashboard.welcome') }}"
                                class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50"
                            >
                                Go to dashboard
                                <svg
                                    class="size-4 shrink-0"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                        @else
                            <a
                                href="{{ route('dashboard.login') }}"
                                class="inline-flex items-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:bg-blue-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50"
                            >
                                Login as Admin
                                <svg
                                    class="size-4 shrink-0"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
