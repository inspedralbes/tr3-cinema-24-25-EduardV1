<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" 
                   name="date" 
                   id="date" 
                   value="{{ old('date', $session->date ?? '') }}"
                   min="{{ date('Y-m-d') }}"
                   required
                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>

        <div>
            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
            <select name="time" 
                    id="time" 
                    required
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @foreach(['16:00', '18:00', '20:00', '22:00'] as $timeOption)
                    <option value="{{ $timeOption }}" {{ (old('time', $session->time ?? '') == $timeOption) ? 'selected' : '' }}>
                        {{ $timeOption }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="movie_search" class="block text-sm font-medium text-gray-700">Search Movie</label>
        <div class="mt-1 flex rounded-md shadow-sm">
            <input type="text" 
                   id="movie_search" 
                   class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300">
            <button type="button" 
                    onclick="searchMovies()"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-r-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Search
            </button>
        </div>
        <div id="movie_results" class="mt-2 space-y-2"></div>
    </div>

    <div id="selected_movie" class="hidden bg-gray-50 p-4 rounded-md">
        <h3 class="text-lg font-medium text-gray-900">Selected Movie</h3>
        <div class="mt-2 flex items-start space-x-4">
            <img id="movie_poster" class="w-24 h-36 object-cover rounded">
            <div>
                <input type="hidden" name="movie[title]" id="movie_title_input">
                <input type="hidden" name="movie[director]" id="movie_director_input">
                <input type="hidden" name="movie[year]" id="movie_year_input">
                <input type="hidden" name="movie[plot]" id="movie_plot_input">
                <input type="hidden" name="movie[poster]" id="movie_poster_input">
                
                <p id="movie_title" class="text-sm font-medium text-gray-900"></p>
                <p id="movie_year" class="text-sm text-gray-500"></p>
                <p id="movie_director" class="text-sm text-gray-500"></p>
                <p id="movie_plot" class="text-sm text-gray-500 mt-2"></p>
            </div>
        </div>
    </div>

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input type="checkbox" 
                   name="is_special_day" 
                   id="is_special_day"
                   value="1"
                   {{ old('is_special_day', $session->is_special_day ?? false) ? 'checked' : '' }}
                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
        </div>
        <div class="ml-3 text-sm">
            <label for="is_special_day" class="font-medium text-gray-700">Special Day</label>
            <p class="text-gray-500">Enable reduced prices for this session</p>
        </div>
    </div>

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input type="checkbox" 
                   name="enable_vip" 
                   id="enable_vip"
                   value="1"
                   {{ old('enable_vip', $session->enable_vip ?? true) ? 'checked' : '' }}
                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
        </div>
        <div class="ml-3 text-sm">
            <label for="enable_vip" class="font-medium text-gray-700">Enable VIP</label>
            <p class="text-gray-500">Enable VIP seating (Row F)</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function searchMovies() {
    const query = document.getElementById('movie_search').value;
    if (!query) return;

    try {
        const response = await fetch(`/admin/movies/search?query=${encodeURIComponent(query)}`);
        const data = await response.json();

        if (data.status === 'success') {
            const resultsDiv = document.getElementById('movie_results');
            resultsDiv.innerHTML = '';

            data.data.forEach(movie => {
                const movieDiv = document.createElement('div');
                movieDiv.className = 'flex items-center p-2 hover:bg-gray-50 cursor-pointer rounded';
                movieDiv.onclick = () => selectMovie(movie);
                
                movieDiv.innerHTML = `
                    <img src="${movie.Poster}" alt="${movie.Title}" class="w-12 h-16 object-cover rounded mr-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">${movie.Title}</div>
                        <div class="text-sm text-gray-500">${movie.Year}</div>
                    </div>
                `;
                
                resultsDiv.appendChild(movieDiv);
            });
        }
    } catch (error) {
        console.error('Error searching movies:', error);
    }
}

function selectMovie(movie) {
    document.getElementById('selected_movie').classList.remove('hidden');
    document.getElementById('movie_results').innerHTML = '';
    document.getElementById('movie_search').value = '';

    // Update hidden inputs
    document.getElementById('movie_title_input').value = movie.Title;
    document.getElementById('movie_director_input').value = movie.Director;
    document.getElementById('movie_year_input').value = movie.Year;
    document.getElementById('movie_plot_input').value = movie.Plot;
    document.getElementById('movie_poster_input').value = movie.Poster;

    // Update display
    document.getElementById('movie_poster').src = movie.Poster;
    document.getElementById('movie_title').textContent = movie.Title;
    document.getElementById('movie_year').textContent = movie.Year;
    document.getElementById('movie_director').textContent = movie.Director;
    document.getElementById('movie_plot').textContent = movie.Plot;
}
</script>
@endpush