<aside class="w-64 bg-gray-800 text-white h-screen flex flex-col">
    <style>
        .icon-spacing {
            margin-right: 1rem; /* Adjust as needed */
        }
    </style>
    <div class="p-6 flex flex-col flex-grow">
        <!-- Brand Logo and Navigation -->
        <div class="flex items-center mb-6">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <!-- Optional sidebar brand logo here if needed -->
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-grow">
            <ul class="space-y-2">
                <!-- Sidebar Links -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-tachometer-alt icon-spacing"></i>
                        {{ __('Dashboard') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('accounts.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-money-bill icon-spacing"></i>
                        {{ __('Accounts') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.all') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-exchange-alt icon-spacing"></i>
                        {{ __('All Transactions') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('investments.all') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-bullseye icon-spacing"></i>
                        {{ __('All Investments') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('cryptos.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-wallet icon-spacing"></i>
                        {{ __('Crypto Currencies') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('wallets.index') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-piggy-bank icon-spacing"></i>
                        {{ __('Investment Wallets') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition duration-150">
                        <i class="fas fa-user icon-spacing"></i>
                        {{ __('Profile') }}
                    </a>
                </li>
                <!-- Add more links here if needed -->
            </ul>
        </nav>
    </div>
</aside>
