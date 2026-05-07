@extends('layouts.sass')
@section('title', 'Find Doctors Nearby')


@section('content')

 <div class="flex-1 flex items-center justify-center pt-28 pb-16 px-6">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-[#318069]/10 rounded-full flex items-center justify-center mx-auto mb-4"><i
                        class="ri-user-line text-4xl text-[#318069] w-10 h-10 flex items-center justify-center"></i>
                </div>
                <p class="text-gray-600 font-bold">Log in to your doctor account</p>
            </div>
            <form class="bg-white border-2 border-gray-200 rounded-2xl p-8 shadow-lg">
                <div class="space-y-6">
                    <div><label class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2"><i
                                    class="ri-mail-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                            </div><input placeholder="doctor@example.com" required=""
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base"
                                type="email" value="">
                        </div>
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2"><i
                                    class="ri-lock-line text-gray-400 text-xl w-5 h-5 flex items-center justify-center"></i>
                            </div><input placeholder="••••••••" required=""
                                class="w-full pl-12 pr-12 py-3 border-2 border-gray-300 rounded-lg focus:border-[#318069] focus:outline-none text-base"
                                type="password" value=""><button type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer"><i
                                    class="ri-eye-line text-xl w-5 h-5 flex items-center justify-center"></i></button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between"><label
                            class="flex items-center gap-2 cursor-pointer"><input
                                class="w-4 h-4 text-[#318069] border-gray-300 rounded focus:ring-[#318069] cursor-pointer"
                                type="checkbox"><span class="text-sm text-gray-700">Remember me</span></label><a
                            href="#" class="text-sm text-[#318069] font-semibold hover:underline">Forgot password?</a>
                    </div><button type="submit"
                        class="w-full bg-[#318069] hover:bg-[#276854] text-white py-4 rounded-xl font-bold transition-all hover:shadow-xl whitespace-nowrap shadow-lg">Log
                        In<i
                            class="ri-arrow-right-line w-5 h-5 inline-flex items-center justify-center ml-2"></i></button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">Don't have an account? <a href="/register"
                    class="text-[#318069] font-semibold hover:underline">Register as a Doctor</a></p>
            
    </div>
    </div>


@endsection