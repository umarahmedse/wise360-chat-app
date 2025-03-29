<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
     @foreach ($users as $user)
        <div class="flex items-center gap-4">
            
            <div class="flex flex-col">
                <a href="{{route('chat',$user->id)}}" class="text-lg font-bold">{{ $user->name }}</a>
            </div>
        </div>
    @endforeach
</x-layouts.app>
