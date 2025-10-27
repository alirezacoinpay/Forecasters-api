<?php

namespace App\Filament\Resources\UserPredictions;

use App\Filament\Resources\UserPredictions\Pages\CreateUserPrediction;
use App\Filament\Resources\UserPredictions\Pages\EditUserPrediction;
use App\Filament\Resources\UserPredictions\Pages\ListUserPredictions;
use App\Filament\Resources\UserPredictions\Pages\ViewUserPrediction;
use App\Filament\Resources\UserPredictions\Schemas\UserPredictionForm;
use App\Filament\Resources\UserPredictions\Schemas\UserPredictionInfolist;
use App\Filament\Resources\UserPredictions\Tables\UserPredictionsTable;
use App\Models\UserPrediction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class UserPredictionResource extends Resource
{
    protected static ?string $model = UserPrediction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getCreateAuthorizationResponse(): Response
    {
        return Response::deny('You cannot create predictions.');
    }

    public static function getDeleteAuthorizationResponse($record): Response
    {
        return Response::deny('Deleting predictions is not allowed.');
    }
    public static function getEditAuthorizationResponse($record): Response
    {
        return Response::deny('Deleting predictions is not allowed.');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['question', 'question.questionOptions']);
    }

    protected static ?string $recordTitleAttribute = 'Prediction';

    public static function form(Schema $schema): Schema
    {
        return UserPredictionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserPredictionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserPredictionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserPredictions::route('/'),
            'view' => ViewUserPrediction::route('/{record}'),
            'edit' => EditUserPrediction::route('/{record}/edit'),
        ];
    }
}
