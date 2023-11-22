<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Информация о промокоде
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="color-gray">
                        <a href="/coupons"><< Вернуться к странице промокодов</a>
                    </div>
                    <div class="mt-3">
                        @if($coupon)
                            Промокод <b>{{ $coupon->promocode }}</b>: скидка {{ $coupon->discount }}% на товары категории "{{ $coupon->category }}"
                        @else
                            Промокод введен неверно
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
