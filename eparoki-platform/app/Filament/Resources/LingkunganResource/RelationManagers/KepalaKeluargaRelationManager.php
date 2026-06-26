<?php

namespace App\Filament\Resources\LingkunganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KepalaKeluargaRelationManager extends RelationManager
{
    protected static string $relationship = 'kepalaKeluarga';

    protected static ?string $title = 'Daftar Kepala Keluarga';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kk')
                    ->label('Nama Kepala Keluarga')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('jumlah_anggota')
                    ->label('Jumlah Anggota')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required(),
                    
                Forms\Components\TextInput::make('no_telp')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_kk')
            ->columns([
                Tables\Columns\TextColumn::make('nama_kk')
                    ->label('Nama Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('jumlah_anggota')
                    ->label('Anggota')
                    ->suffix(' jiwa')
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('No. Telepon')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah KK'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Data KK')
            ->emptyStateDescription('Tambahkan data kepala keluarga untuk lingkungan ini')
            ->emptyStateIcon('heroicon-o-users');
    }
}
