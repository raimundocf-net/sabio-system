<?php

namespace App\Livewire\Units; // Verifique o namespace

use App\Models\Unit;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class ManageUnit extends Component
{
    public ?Unit $unit = null;

    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|string|max:255')]
    public string $municipality = '';

    // A regra de 'unique' será ajustada no método mount para edição
    #[Rule('required|string|max:10')] // CNES geralmente tem 7 dígitos, mas string é mais seguro. Ajuste max se necessário.
    public string $cnes = '';

    public bool $isEditing = false;
    public string $pageTitle = ''; // Título dinâmico para a página

    /**
     * Monta o componente.
     * Se um ID de unidade for fornecido, carrega para edição.
     * Caso contrário, prepara para criação.
     */
    public function mount(?int $unitId = null): void
    {
        if ($unitId) {
            $this->unit = Unit::findOrFail($unitId);
            $this->name = $this->unit->name;
            $this->municipality = $this->unit->municipality;
            $this->cnes = $this->unit->cnes;
            $this->isEditing = true;
            $this->pageTitle = __('Editar Unidade de Saúde');
        } else {
            $this->pageTitle = __('Nova Unidade de Saúde');
            // $this->unit = new Unit(); // Opcional, pode ajudar com type hinting se necessário
        }
    }

    /**
     * Retorna as regras de validação dinâmicas.
     * Importante para a unicidade do CNES durante a edição.
     */
    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'cnes' => 'required|string|max:10|unique:units,cnes',
        ];

        if ($this->isEditing && $this->unit) {
            $rules['cnes'] .= ',' . $this->unit->id; // Ignora o CNES da unidade atual na verificação de unicidade
        }

        return $rules;
    }

    /**
     * Salva a unidade (criação ou atualização).
     */
    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->isEditing && $this->unit) {
            $this->unit->update($validatedData);
            session()->flash('status', __('Unidade atualizada com sucesso!'));
        } else {
            Unit::create($validatedData);
            session()->flash('status', __('Unidade criada com sucesso!'));
        }

        $this->dispatch('unit-saved'); // Despacha evento para o componente de listagem
        $this->redirectRoute('units.index', navigate: true); // Usando `Maps:true` para navegação SPA do Livewire v3
    }

    /**
     * Cancela a operação e redireciona para a lista.
     */
    public function cancel(): void
    {
        $this->redirectRoute('units.index', navigate: true);
    }

    /**
     * Renderiza o componente.
     */
    public function render()
    {
        return view('livewire.units.manage-unit');
    }
}
