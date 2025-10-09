@php($urls = $urls ?? [])
@if(!empty($urls))
    <div class="space-y-4">
        @foreach($urls as $i => $url)
            <div class="border rounded-lg overflow-hidden bg-white/5">
                <div class="px-3 py-2 text-sm text-gray-300 flex items-center justify-between">
                    <span>PDF Preview {{ $i + 1 }}</span>
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="text-primary-400 hover:text-primary-300">Open in new tab</a>
                </div>
                <div class="aspect-[3/4] w-full">
                    <iframe
                        src="{{ $url }}#toolbar=0&navpanes=0&scrollbar=1"
                        class="w-full h-[720px] md:h-[640px] lg:h-[560px] bg-white"
                        loading="lazy"
                        title="PDF Preview {{ $i + 1 }}"
                    ></iframe>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-sm text-gray-400">No PDFs to preview.</div>
@endif
