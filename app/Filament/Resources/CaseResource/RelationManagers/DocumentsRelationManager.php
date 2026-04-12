<?php

namespace App\Filament\Resources\CaseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('File')
                    ->disk('local')
                    ->directory('documents')
                    ->required()
                    ->maxSize(10240)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename'),
                Tables\Columns\TextColumn::make('mime_type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('size_for_humans')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Uploaded By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Uploaded'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $path = $data['path'];
                        $disk = Storage::disk('local');

                        $data['filename'] = basename($path);
                        $data['disk'] = 'local';
                        $data['mime_type'] = $disk->mimeType($path);
                        $data['size'] = $disk->size($path);
                        $data['uploaded_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        return Storage::disk($record->disk)->download($record->path, $record->filename);
                    }),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
