<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EditEnv extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->email === 'jclllobrera@miescor.ph';
    }

    protected string $view = 'filament.pages.edit-env';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Edit Env';

    protected static string $routePath = 'edit-env';

    public function mount(): void
    {
        $this->form->fill([
            'envFile' => file_get_contents(base_path('.env')),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                CodeEditor::make('envFile')
                    ->label('The environment variables determine the runtime configuration of this application. Handle with care.'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        file_put_contents(base_path('.env'), $data['envFile']);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
