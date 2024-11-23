@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Scan Result: {{ $host->ip }}</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Details for IP: {{ $host->ip }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>IP Address</th>
                            <td>{{ $host->ip }}</td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <td>{{ $host->country }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $host->city }}</td>
                        </tr>
                        <tr>
                            <th>ISP</th>
                            <td>{{ $host->isp }}</td>
                        </tr>
                        <tr>
                            <th>ASN</th>
                            <td>{{ $host->asn }}</td>
                        </tr>
                        <tr>
                            <th>Open Ports</th>
                            <td>
                                @php
                                    $ports = json_decode($host->ports);
                                    echo is_array($ports) ? implode(', ', $ports) : 'N/A';
                                @endphp
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- CVE Section -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Vulnerabilities (CVE)</h5>
                    </div>
                    <div class="card-body">
                        @if ($host->vulns)
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 20%;">CVE ID</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($host->vulns) as $vuln)
                                        <tr>
                                            <td>{{ $vuln }}</td>
                                            <td>{{ json_decode($host->cve_details)->{$vuln} ?? 'No details available' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p><em>No vulnerabilities detected.</em></p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Service Banners Section -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Service Banners</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $serviceBanners = json_decode($host->service_banners);
                        @endphp
                        @if ($serviceBanners && is_array($serviceBanners))
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Port</th>
                                        <th>Service Banner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($serviceBanners as $service)
                                        <tr>
                                            <td>{{ $service->port }}</td>
                                            <td>{{ $service->banner }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p><em>No service banners available.</em></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('dashboard.index') }}" class="btn btn-secondary mt-4">Back to Dashboard</a>
    </div>
@endsection
