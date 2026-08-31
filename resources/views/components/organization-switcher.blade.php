<div class="flex items-center gap-3">

    <span class="text-xs font-medium uppercase tracking-wide text-slate-400">
        Organization
    </span>

    @if($organizations->count() > 1)

        <form
            method="POST"
            id="organization-switcher"
        >
            @csrf

            <select
                name="organization_id"
                onchange="
                    this.form.action = '{{ url('/organizations') }}/' + this.value + '/switch';
                    this.form.submit();
                "
                class="min-w-56 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
            >

                @foreach($organizations as $item)

                    <option
                        value="{{ $item->id }}"
                        @selected($item->id === $organization->id)
                    >
                        {{ $item->name }}
                    </option>

                @endforeach

            </select>

        </form>

    @else

        <span class="text-sm font-semibold text-slate-900">
            {{ $organization->name }}
        </span>

    @endif

</div>
