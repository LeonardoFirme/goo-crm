// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
import IMask from 'imask';

window.Alpine = Alpine;
Alpine.start();

// Lógica de Máscaras Global
document.addEventListener('DOMContentLoaded', () => {
    const applyMasks = () => {
        // CPF / CNPJ
        document.querySelectorAll('.mask-tax-id').forEach(el => {
            IMask(el, {
                mask: [
                    { mask: '000.000.000-00', type: 'CPF' },
                    { mask: '000.000.000/0000-00', type: 'CNPJ' }
                ],
                dispatch: (appended, dynamicMasked) => {
                    const number = (dynamicMasked.value + appended).replace(/\D/g, '');
                    return number.length <= 11 ? dynamicMasked.compiledMasks[0] : dynamicMasked.compiledMasks[1];
                }
            });
        });

        // Telefone
        document.querySelectorAll('.mask-phone').forEach(el => {
            IMask(el, {
                mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }]
            });
        });

        // Preço / Moeda (Padrão BRL: 1.000,00)
        document.querySelectorAll('.mask-money').forEach(el => {
            IMask(el, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,                 // Duas casas decimais
                        signed: false,            // Apenas valores positivos
                        thousandsSeparator: '.',  // Ponto para milhar
                        padFractionalZeros: true, // Força exibição de centavos (ex: ,00)
                        normalizeZeros: true,     // Remove zeros desnecessários à esquerda
                        radix: ',',               // Vírgula para decimal
                        mapToRadix: ['.'],        // Mapeia o ponto do teclado numérico para vírgula
                        min: 0
                    }
                }
            });
        });
    };

    applyMasks();

    // Suporte para conteúdos carregados via Livewire/Alpine dinâmico
    document.addEventListener('alpine:initialized', applyMasks);
});