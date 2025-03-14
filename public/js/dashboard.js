// Global variables for charts
let ordersChart;
let categoryChart;

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get data from the data container
    const dashboardData = window.dashboardData || {};
    
    // Initialize charts with the data
    initializeCharts(
        dashboardData.dates || [], 
        dashboardData.orderCounts || [], 
        dashboardData.revenues || [],
        dashboardData.monthlyDates || [],
        dashboardData.monthlyOrderCounts || [], 
        dashboardData.monthlyRevenues || []
    );
    
    // Set up event listeners for UI interactions
    setupEventListeners();
    
    // Initialize tooltips
    initializeTooltips();
});

// Initialize all charts
function initializeCharts(dates, orderCounts, revenues, monthlyDates, monthlyOrderCounts, monthlyRevenues) {
    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                    label: 'Orders',
                    data: orderCounts,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y'
                },
                {
                    label: 'Revenue ($)',
                    data: revenues,
                    borderColor: '#1cc88a',
                    borderDash: [5, 5],
                    tension: 0.3,
                    fill: false,
                    pointBackgroundColor: '#1cc88a',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Orders'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });

    // Store data for later use
    window.chartData = {
        weekly: {
            labels: dates,
            orderCounts: orderCounts,
            revenues: revenues
        },
        monthly: {
            labels: monthlyDates,
            orderCounts: monthlyOrderCounts,
            revenues: monthlyRevenues
        }
    };

    // Category Distribution
    const categoryNames = window.dashboardData.categoryNames || [];
    const categoryItemCounts = window.dashboardData.categoryItemCounts || [];

    // Generate random colors for categories
    const backgroundColors = categoryNames.map(() => {
        const r = Math.floor(Math.random() * 200) + 55;
        const g = Math.floor(Math.random() * 200) + 55;
        const b = Math.floor(Math.random() * 200) + 55;
        return `rgba(${r}, ${g}, ${b}, 0.7)`;
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryNames,
            datasets: [{
                data: categoryItemCounts,
                backgroundColor: backgroundColors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 15,
                        padding: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} items (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
}

// Set up all event listeners
function setupEventListeners() {
    // Weekly/Monthly toggle buttons
    document.getElementById('weekly-view').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('monthly-view').classList.remove('active');
        updateChart('weekly');
    });

    document.getElementById('monthly-view').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('weekly-view').classList.remove('active');
        updateChart('monthly');
    });

    // Time range dropdown items
    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            // Update active state
            document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(el =>
                el.classList.remove('active')
            );
            this.classList.add('active');

            // Update button text
            document.getElementById('selected-range').textContent = this.textContent.trim();

            // Update dashboard data based on selected range
            updateDashboardData(this.getAttribute('data-range'));
        });
    });
}

// Initialize Bootstrap tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Update chart based on weekly/monthly selection
function updateChart(timeRange) {
    const data = window.chartData[timeRange];
    if (!data) return;

    ordersChart.data.labels = data.labels;
    ordersChart.data.datasets[0].data = data.orderCounts;
    ordersChart.data.datasets[1].data = data.revenues;
    ordersChart.update();
}

// Update dashboard data based on selected time range
function updateDashboardData(range) {
    // Show loading state
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.classList.add('loading');
        card.style.opacity = '0.7';
    });

    // Add a loading spinner to dropdown button
    const dropdownBtn = document.getElementById('timeRangeDropdown');
    const originalBtnText = dropdownBtn.innerHTML;
    dropdownBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Loading...`;
    dropdownBtn.disabled = true;

    fetch(`/api/dashboard-data?range=${range}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response error: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dashboard data loaded:', data);

            // Update summary cards
            if (document.getElementById('order-count')) {
                document.getElementById('order-count').innerText = data.orderCount;
            }

            if (document.getElementById('revenue-value')) {
                document.getElementById('revenue-value').innerText = '$' + data.revenue.toFixed(2);
            }

            // Update top categories
            updateTopCategories(data.topCategories);

            // Update chart data
            updateChartWithApiData(data);

            // Remove loading state
            cards.forEach(card => {
                card.classList.remove('loading');
                card.style.opacity = '1';
            });

            // Restore dropdown button
            dropdownBtn.innerHTML = originalBtnText;
            dropdownBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error fetching dashboard data:', error);

            // Remove loading state
            cards.forEach(card => {
                card.classList.remove('loading');
                card.style.opacity = '1';
            });

            // Restore dropdown button
            dropdownBtn.innerHTML = originalBtnText;
            dropdownBtn.disabled = false;

            // Show detailed error message
            alert(`Failed to load dashboard data: ${error.message}`);
        });
}

// Update the top categories list
function updateTopCategories(categories) {
    const topCategoriesList = document.getElementById('top-categories-list');
    if (!topCategoriesList) return;

    topCategoriesList.innerHTML = '';

    if (!categories || categories.length === 0) {
        topCategoriesList.innerHTML = '<li class="list-group-item text-center text-muted">No data available</li>';
        return;
    }

    categories.forEach((category, index) => {
        const colorClasses = ['primary', 'success', 'info', 'warning', 'secondary'];
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            <div>
                <span class="badge bg-${colorClasses[index] || 'light'} me-2">${index + 1}</span>
                ${category.name}
            </div>
            <span class="fw-bold">$${parseFloat(category.revenue).toFixed(2)}</span>
        `;
        topCategoriesList.appendChild(li);
    });
}

// Update the charts with API data
function updateChartWithApiData(data) {
    if (!ordersChart || !data.chartData) return;

    const labels = data.chartData.map(item => item.label);
    const orders = data.chartData.map(item => item.orders);
    const revenue = data.chartData.map(item => item.revenue);

    ordersChart.data.labels = labels;
    ordersChart.data.datasets[0].data = orders;
    ordersChart.data.datasets[1].data = revenue;
    ordersChart.update();
}