# Laravel Event-Sourcing demo application

Event Sourcing is a pattern that suggests a different approach to handle state. To keep it short, instead of storing
the current state in the database, you store every single event about what the user did in your application, and you
can derive what the current state of the application is by replaying all events that have happened.

## Setup

- Clone the repository
- Copy the `.env.example` to `.env`
- Change the `DB_*` env keys there to your desired database
- Generate the app master key with `php artisan key:generate`
- Migrate `php artisan migrate`
- Access [http://localhost:8000](http://localhost:8000)
- Register and Play around with todos
- When you have a few tasks, truncate the `tasks` table and run `php artisan event-projector:rebuild` and answer `yes` to the prompted question
- You should see your application still has the same data at the same state as before.

## Important aspects

- I'm using Phoenix's Context way of thinking (see more [here](https://hexdocs.pm/phoenix/contexts.html))
- Check the [TaskContext](./app/Core/Tasks/TaskContext.php)
- Check the [TasksProjector](./app/Core/Tasks/TasksProjector.php)
- Check the reactor that is responsible of sending a congratulations email to users on second completed task [SecondCompletedTaskReactor](./app/Core/Tasks/Reactors/SecondCompletedTaskReactor.php)
- Check the [tests](./tests/Feature/Core/Tasks/TaskTest.php)
