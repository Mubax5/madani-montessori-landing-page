@if ($faqs->isNotEmpty())
    <section class="section-pad--tight">
        <div class="section-shell--narrow">
            <div class="section-kicker">FAQ</div>
            <h2 class="section-title">Pertanyaan yang sering ditanyakan.</h2>
            <div class="faq-list">
                @foreach ($faqs as $faq)
                    <details class="faq-item">
                        <summary>{{ $faq->question }}</summary>
                        <p>{{ $faq->answer }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endif
