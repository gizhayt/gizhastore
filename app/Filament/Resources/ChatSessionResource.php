<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatSessionResource\Pages;
use App\Models\ChatSession;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table; // ✅ BENAR


// class ChatSessionResource extends Resource
// {
//     protected static ?string $model = ChatSession::class;

//     protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
//     protected static ?string $navigationGroup = 'Client Management';
//     protected static ?int $navigationSort = 3;
//     protected static ?string $recordTitleAttribute = 'id';

//     public static function form(Forms\Form $form): Forms\Form
//     {
//         return $form->schema([
//             Forms\Components\Select::make('client_id')
//                 ->relationship('client', 'name')
//                 ->required()
//                 ->disabled(),

//             Forms\Components\Select::make('admin_id')
//                 ->relationship('admin', 'name')
//                 ->label('Assigned Admin'),

//             Forms\Components\Select::make('status')
//                 ->required()
//                 ->native(false)
//                 ->label('Status')
//                 ->options([
//                     'active' => 'Active',
//                     'closed' => 'Closed',
//                     'pending' => 'Pending',
//                 ]),
//         ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('client.name')
//                     ->label('Client Name')
//                     ->searchable()
//                     ->sortable(),
    
//                 Tables\Columns\TextColumn::make('latest_message')
//                     ->label('Latest Message')
//                     ->getStateUsing(function ($record) {
//                         return \Str::limit($record->messages()->latest()->first()?->message ?? 'No messages', 50);
//                     }),
    
//                 Tables\Columns\TextColumn::make('last_activity')
//                     ->label('Last Activity')
//                     ->getStateUsing(function ($record) {
//                         return $record->messages()->latest()->first()?->created_at;
//                     })
//                     ->dateTime()
//                     ->sortable(),
    
//                 Tables\Columns\TextColumn::make('message_count')
//                     ->label('Messages')
//                     ->getStateUsing(function ($record) {
//                         return $record->messages()->count();
//                     }),
//             ])
//             ->actions([
//                 Tables\Actions\Action::make('view_chat')
//                     ->label('View Chat')
//                     ->url(fn ($record) => self::getUrl('chat', ['chatSessionId' => $record->id]))
//                     ->icon('heroicon-o-chat-bubble-left-right'),
//             ]);
            
//     }
    

//     public static function getRelations(): array
//     {
//         return [];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListChatSessions::route('/'),
//             'create' => Pages\CreateChatSession::route('/create'),
//             'edit' => Pages\EditChatSession::route('/{record}/edit'),
//             // 'chat' => Pages\CustomChattPage::route('/{record}/chat'),
//         ];
//     }
// }
