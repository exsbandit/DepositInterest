<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vadeli Mevduat Hesaplama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('/api/deposit-rates');
                const data = await response.json();

                const currencyOptions = document.getElementById('currency-options');
                data.currencies.forEach(currency => {
                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.classList.add('btn-check');
                    radio.name = 'currency';
                    radio.value = currency;
                    radio.id = `currency-${currency}`;
                    radio.required = true;

                    const label = document.createElement('label');
                    label.classList.add('btn', 'btn-outline-primary', 'me-2');
                    label.setAttribute('for', `currency-${currency}`);
                    label.textContent = currency;

                    currencyOptions.appendChild(radio);
                    currencyOptions.appendChild(label);
                });

                const durationSelect = document.getElementById('duration');
                const manualDurationInput = document.getElementById('manual-duration-container');
                const manualOption = document.createElement('option');
                manualOption.value = 'manual';
                manualOption.textContent = 'Manuel Seçim';
                durationSelect.appendChild(manualOption);

                data.durations.forEach((duration, index) => {
                    const option = document.createElement('option');
                    option.value = duration;
                    option.textContent = `${duration} Gün`;
                    if (index === 0) {
                        option.selected = true;
                    }
                    durationSelect.appendChild(option);
                });

                durationSelect.addEventListener('change', (event) => {
                    if (event.target.value === 'manual') {
                        manualDurationInput.classList.remove('d-none');
                    } else {
                        manualDurationInput.classList.add('d-none');
                    }
                });

                const form = document.getElementById('interest-form');
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const amount = document.getElementById('amount').value;
                    const currency = document.querySelector('input[name="currency"]:checked').value;
                    const duration = durationSelect.value === 'manual'
                        ? document.getElementById('manual-duration').value
                        : durationSelect.value;

                    const response = await fetch('/api/calculate-interest', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ amount, currency, duration })
                    });

                    const results = await response.json();
                    if (response.ok) {
                        displayResults(results);
                    } else {
                        alert(results.message || 'Hesaplama sırasında hata oluştu.');
                    }
                });
            } catch (error) {
                console.error('API hatası:', error);
                alert('Veriler alınırken bir hata oluştu.');
            }
        });

        function displayResults(results) {
            const resultContainer = document.getElementById('result-container');
            resultContainer.innerHTML = '';

            results.forEach(result => {
                const card = document.createElement('div');
                card.classList.add('card', 'mb-3');

                card.innerHTML = `
                    <div class="card-body">
                        <h5 class="card-title">Banka : ${result.bank_name}</h5>
                        <p class="card-text">Vade Baremi: ${result.on_duration}</p>
                        <p class="card-text">Faiz Oranı: ${result.rate}</p>
                        <p class="card-text">Brüt Faiz: ${result.gross_interest}</p>
                        <p class="card-text">Vergi Kesintisi: ${result.tax}</p>
                        <p class="card-text">Net Faiz: ${result.net_interest}</p>
                        <p class="card-text fw-bold">Vade Sonu Net Bakiye: ${result.final_balance}</p>
                    </div>
                `;

                resultContainer.appendChild(card);
            });
        }
    </script>
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="text-center mb-4">Vadeli Mevduat Hesaplama</h1>

    <form id="interest-form" class="card p-4 shadow-sm mb-5">
        <div class="mb-3">
            <label for="amount" class="form-label">Hesap Açılış Tutarı:</label>
            <input type="number" id="amount" name="amount" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <p class="form-label">Para Birimi:</p>
            <div id="currency-options" class="btn-group" role="group"></div>
        </div>

        <div class="mb-3">
            <label for="duration" class="form-label">Vade (Gün):</label>
            <select id="duration" class="form-select"></select>
        </div>

        <div id="manual-duration-container" class="mb-3 d-none">
            <label for="manual-duration" class="form-label">Manuel Vade Günü Girin:</label>
            <input type="number" id="manual-duration" class="form-control" placeholder="Vade günü girin">
        </div>

        <button type="submit" class="btn btn-primary w-100">HESAPLA</button>
    </form>

    <div id="result-container" class="mt-5"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
